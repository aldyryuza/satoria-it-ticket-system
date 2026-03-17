let Auth = {
    module: () => 'auth',

    moduleApi: () => 'api/' + Auth.module(),

    signIn: (elm, e) => {
        e.preventDefault(); // cegah reload

        let link_url = url.base_url(Auth.moduleApi()) + 'login';
        let params = {
            username: $('#username').val(),
            password: $('#password').val()
        };
        let form = $(elm).closest("div.row");
        if (validation.runWithElement(form)) {
            $.ajax({
                type: "POST",
                url: link_url,
                data: params,
                dataType: "json",
                beforeSend: () => {
                    message.loadingProses('Proses Login...');
                },
                success: function (response) {
                    message.closeLoading();

                    if (response.is_valid) {
                        localStorage.setItem('auth_token', response.token);
                        localStorage.setItem('auth_user', JSON.stringify(response.data));

                        if (response.data.roles && response.data.roles.length > 1) {
                            Auth.showRolePicker(response.data.roles, response.token, response.data);
                        } else {
                            Auth.saveSession(response.token, response.data);
                        }
                    } else {
                        message.sweetError('Informasi', response.message || 'Login gagal!');
                    }
                },
                error: function (xhr) {
                    message.closeLoading();

                    // 🧠 Ambil pesan dari response JSON (jika ada)
                    let msg = 'Terjadi kesalahan pada server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.statusText) {
                        msg = xhr.statusText;
                    }

                    console.log('Error:', xhr);
                    message.sweetError('Informasi', msg);
                }
            });
        }
    },

    showRolePicker: (roles, token, userData) => {
        let options = roles.map((r) => `<option value="${r.id}">${r.role_name || r.name || 'Role ' + r.id}</option>`).join('');
        Swal.fire({
            title: 'Pilih Role',
            html: `<div class="form-group text-left"><label>Pilih role yang aktif</label><select id="auth-role-select" class="form-control">${options}</select></div>`,
            showCancelButton: true,
            confirmButtonText: 'Lanjut',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const roleId = $('#auth-role-select').val();
                if (!roleId) {
                    Swal.showValidationMessage('Silakan pilih role.');
                }
                return roleId;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Auth.saveSession(token, userData, result.value);
            }
        });
    },

    saveSession: (token, userData, roleId = null) => {
        $.ajax({
            type: "POST",
            url: url.base_url('auth') + 'save_session',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                token: token,
                user: JSON.stringify(userData),
                role_id: roleId,
            },
            dataType: "json",
            success: function (response) {
                if (response.is_valid) {
                    window.location.href = url.base_url('dashboard');
                } else {
                    message.sweetError('Informasi', response.message || 'Gagal menyimpan sesi.');
                }
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan sesi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                message.sweetError('Informasi', msg);
            }
        });
    },

    signOut: () => {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Hapus data auth
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user');

                // Tampilkan pesan sukses lalu redirect
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Anda telah logout.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    window.location.href = url.base_url('auth/logout');
                }, 1500);
            }
        });
    }


};

$(function () {
    // on enter key press on #form-login
    $('#form-login').on('keypress', function (e) {
        if (e.which === 13) {
            Auth.signIn(this, e);
        }
    });
});
