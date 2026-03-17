let RoleManagement = {
    module: () => 'master/roles',

    moduleApi: () => 'api/' + RoleManagement.module(),

    add: () => {
        window.location.href = url.base_url(RoleManagement.module()) + 'create';
    },

    edit: (id) => {
        window.location.href = url.base_url(RoleManagement.module()) + 'edit/' + id;
    },

    detail: (id) => {
        window.location.href = url.base_url(RoleManagement.module()) + 'detail/' + id;
    },

    back: () => {
        window.location.href = url.base_url(RoleManagement.module());
    },

    getData: () => {
        if ($.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable().destroy();
        }

        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url.base_url(RoleManagement.moduleApi()) + 'getData',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'id' },
                { data: 'role_name' },
                { data: 'description' },
                { data: 'created_at' },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    render: (d, t, r) => `
                        <button class="btn btn-sm btn-info" onclick="RoleManagement.detail(${r.id})">
                            <i class="bx bx-detail"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="RoleManagement.edit(${r.id})">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="RoleManagement.delete(${r.id})">
                            <i class="bx bx-trash"></i>
                        </button>
                    `
                }
            ]
        });
    },

    getPostData: () => ({
        id: $('#id').val(),
        role_name: $('#role_name').val(),
        description: $('#description').val()
    }),

    submit: () => {
        if (validation.run() === 1) {
            $.ajax({
                url: url.base_url(RoleManagement.moduleApi()) + 'submit',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                data: RoleManagement.getPostData(),
                success: function (res) {
                    if (res.is_valid) {
                        message.sweetSuccess(res.message);
                        RoleManagement.back();
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
                    url: url.base_url(RoleManagement.moduleApi()) + 'delete/' + id,
                    type: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    },
                    success: function () {
                        message.sweetSuccess('Terhapus');
                        RoleManagement.getData();
                    }
                });
            }
        });
    }
};

$(function () {
    if ($('#data-table').length) {
        RoleManagement.getData();
    }
});
