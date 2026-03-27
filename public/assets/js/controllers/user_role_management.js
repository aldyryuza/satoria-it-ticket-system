let UserRoleManagement = {
    module: () => 'master/user-roles',

    moduleApi: () => 'api/' + UserRoleManagement.module(),

    add: () => {
        window.location.href = url.base_url(UserRoleManagement.module()) + 'create';
    },

    edit: (id) => {
        window.location.href = url.base_url(UserRoleManagement.module()) + 'edit/' + id;
    },

    detail: (id) => {
        window.location.href = url.base_url(UserRoleManagement.module()) + 'detail/' + id;
    },

    back: () => {
        window.location.href = url.base_url(UserRoleManagement.module());
    },

    getData: () => {
        if ($.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable().destroy();
        }

        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url.base_url(UserRoleManagement.moduleApi()) + 'getData',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'id' },
                { data: 'user_name' },
                { data: 'role_name' },
                { data: 'created_at' },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    render: (d, t, r) => `
                        <button class="btn btn-sm btn-info" onclick="UserRoleManagement.detail(${r.id})">
                            <i class="bx bx-detail"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="UserRoleManagement.edit(${r.id})">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="UserRoleManagement.delete(${r.id})">
                            <i class="bx bx-trash"></i>
                        </button>
                    `
                }
            ]
        });
    },

    getPostData: () => ({
        id: $('#id').val(),
        user_id: $('#user_id').val(),
        role_id: $('#role_id').val()
    }),

    submit: () => {
        if (validation.run() === 1) {
            $.ajax({
                url: url.base_url(UserRoleManagement.moduleApi()) + 'submit',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                data: UserRoleManagement.getPostData(),
                success: function (res) {
                    if (res.is_valid) {
                        message.sweetSuccess(res.message);
                        UserRoleManagement.back();
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
                    url: url.base_url(UserRoleManagement.moduleApi()) + 'delete/' + id,
                    type: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    },
                    success: function () {
                        message.sweetSuccess('Terhapus');
                        UserRoleManagement.getData();
                    }
                });
            }
        });
    }
};

$(function () {
    if ($('#data-table').length) {
        UserRoleManagement.getData();
    }

    if ($('.select2').length) {
        $('.select2').select2();
    }
});
