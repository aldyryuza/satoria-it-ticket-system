let UserManagement = {
    module: () => 'users',

    moduleApi: () => 'api/' + UserManagement.module(),

    add: () => {
        window.location.href = url.base_url(UserManagement.module()) + 'create';
    },

    edit: (id) => {
        window.location.href = url.base_url(UserManagement.module()) + 'edit/' + id;
    },

    detail: (id) => {
        window.location.href = url.base_url(UserManagement.module()) + 'detail/' + id;
    },

    back: () => {
        window.location.href = url.base_url(UserManagement.module());
    },

    getData: () => {
        if ($.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable().destroy();
        }

        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url.base_url(UserManagement.moduleApi()) + 'getData',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'id' },
                { data: 'name' },
                { data: 'username' },
                { data: 'email' },
                { data: 'company_name' },
                { data: 'division_name' },
                { data: 'is_active' },
                { data: 'created_at' },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    render: (d, t, r) => `
                        <button class="btn btn-sm btn-info" onclick="UserManagement.detail(${r.id})">
                            <i class="bx bx-detail"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="UserManagement.edit(${r.id})">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="UserManagement.delete(${r.id})">
                            <i class="bx bx-trash"></i>
                        </button>
                    `
                }
            ]
        });
    },

    getPostData: () => ({
        id: $('#id').val(),
        company_id: $('#company_id').val(),
        division_id: $('#division_id').val(),
        name: $('#name').val(),
        username: $('#username').val(),
        email: $('#email').val(),
        password: $('#password').val(),
        is_active: $('#is_active').is(':checked') ? 1 : 0
    }),

    submit: () => {
        if (validation.run() === 1) {
            $.ajax({
                url: url.base_url(UserManagement.moduleApi()) + 'submit',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                data: UserManagement.getPostData(),
                success: function (res) {
                    if (res.is_valid) {
                        message.sweetSuccess(res.message);
                        UserManagement.back();
                    } else {
                        message.sweetError(res.message);
                    }
                }
            });
        }
    },

    delete: (id) => {
        Swal.fire({
            title: 'Hapus?',
            text: 'Yakin hapus?',
            icon: 'warning',
            showCancelButton: true
        }).then((r) => {
            if (r.isConfirmed) {
                $.ajax({
                    url: url.base_url(UserManagement.moduleApi()) + 'delete/' + id,
                    type: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    },
                    success: function () {
                        message.sweetSuccess('Terhapus');
                        UserManagement.getData();
                    }
                });
            }
        });
    }
};

$(function () {
    if ($('#data-table').length) {
        UserManagement.getData();
    }
});
