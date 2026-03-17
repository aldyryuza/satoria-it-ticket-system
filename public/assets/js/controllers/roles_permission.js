const updatemode = $('#update').val() ?? 0;
const delmode = $('#delete').val() ?? 0;

let RolePermission = {
    module: () => 'roles-permission',
    moduleApi: () => 'api/' + RolePermission.module(),

    add: () => {
        window.location.href = url.base_url(RolePermission.module()) + 'create';
    },
    edit: (id) => {
        window.location.href = url.base_url(RolePermission.module()) + 'edit/' + id;
    },
    detail: (id) => {
        window.location.href = url.base_url(RolePermission.module()) + 'detail/' + id;
    },
    back: () => {
        window.location.href = url.base_url(RolePermission.module());
    },

    getData: () => {
        if ($.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable().destroy();
        }

        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            autoWidth: false,
            destroy: true,
            ajax: {
                url: url.base_url(RolePermission.moduleApi()) + 'getData',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get(),
                }
            },
            columns: [
                {
                    data: null,
                    name: 'select',
                    orderable: false,
                    searchable: false,
                    className: 'text-center align-middle',
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="check-item" value="${row.id}" />`;
                    }
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'id', name: 'role_permissions.id' },
                { data: 'role_name', name: 'roles.role_name' },
                { data: 'menu_name', name: 'menus.menu_name' },
                {
                    data: 'can_view', name: 'role_permissions.can_view',
                    render: function (data, type, row) {
                        if (row.can_view) {
                            return '✅';
                        } else {
                            return '❌';
                        }
                    }
                },
                {
                    data: 'can_create', name: 'role_permissions.can_create',
                    render: function (data, type, row) {
                        if (row.can_view) {
                            return '✅';
                        } else {
                            return '❌';
                        }
                    }
                },
                {
                    data: 'can_update', name: 'role_permissions.can_update',
                    render: function (data, type, row) {
                        if (row.can_view) {
                            return '✅';
                        } else {
                            return '❌';
                        }
                    }
                },
                {
                    data: 'can_delete', name: 'role_permissions.can_delete',
                    render: function (data, type, row) {
                        if (row.can_view) {
                            return '✅';
                        } else {
                            return '❌';
                        }
                    }
                },
                {
                    data: 'can_print', name: 'role_permissions.can_print',
                    render: function (data, type, row) {
                        if (row.can_view) {
                            return '✅';
                        } else {
                            return '❌';
                        }
                    }
                },
                {
                    data: 'created_at', name: 'role_permissions.created_at',
                    render: function (data, type, row) {
                        if (row.can_view) {
                            return '✅';
                        } else {
                            return '❌';
                        }
                    }
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center align-middle',
                    render: function (data, type, row) {
                        let btns = '';
                        if (updatemode == 1) {
                            btns += `<button class="btn btn-sm btn-info" onclick="RolePermission.detail(${row.id})"><i class="bx bx-detail"></i></button> `;
                            btns += `<button class="btn btn-sm btn-warning" onclick="RolePermission.edit(${row.id})"><i class="bx bx-edit"></i></button> `;
                        }
                        if (delmode == 1) {
                            btns += `<button class="btn btn-sm btn-danger" onclick="RolePermission.delete(this,event)" data-id="${row.id}"><i class="bx bx-trash"></i></button>`;
                        }
                        return `<div class="d-flex justify-content-center gap-1">${btns}</div>`;
                    }
                }
            ]
        });

        $('#select-all').off('change').on('change', function () {
            let checked = $(this).is(':checked');
            $('.check-item').prop('checked', checked);
        });

        $('#data-table').off('change', '.check-item').on('change', '.check-item', function () {
            let total = $('.check-item').length;
            let checked = $('.check-item:checked').length;
            $('#select-all').prop('checked', total > 0 && total === checked);
        });
    },

    getPostData: () => {
        return {
            id: $('#id').val(),
            role_id: $('#role_id').val(),
            menu_id: $('#menu_id').val(),
            can_view: $('#can_view').is(':checked') ? 1 : 0,
            can_create: $('#can_create').is(':checked') ? 1 : 0,
            can_update: $('#can_update').is(':checked') ? 1 : 0,
            can_delete: $('#can_delete').is(':checked') ? 1 : 0,
            can_print: $('#can_print').is(':checked') ? 1 : 0,
        };
    },

    bindSelectAll: () => {
        $('#can_all').on('change', function () {
            let checked = $(this).is(':checked');
            $('.check-permission').prop('checked', checked);
        });
    },

    submit: () => {
        let params = RolePermission.getPostData();
        let _url = url.base_url(RolePermission.moduleApi()) + 'submit';
        if (validation.run() === 1) {
            $.ajax({
                type: 'POST',
                url: _url,
                data: params,
                dataType: 'json',
                headers: { 'Authorization': 'Bearer ' + Token.get() },
                beforeSend: () => { message.loadingProses('Proses Simpan...'); },
                success: function (response) {
                    message.closeLoading();
                    if (response.is_valid) {
                        message.sweetSuccess(response.message);
                        RolePermission.back();
                    } else {
                        message.sweetError(response.message);
                    }
                }
            });
        }
    },

    delete: (elm, e) => {
        let id = $(elm).data('id');
        let _url = url.base_url(RolePermission.moduleApi()) + 'delete/' + id;
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data ini akan dihapus dan tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: _url,
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + Token.get() },
                    success: function (res) {
                        Swal.fire('Terhapus!', res.message || 'Data berhasil dihapus.', 'success');
                        RolePermission.getData();
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
        return false;
    },

    bulkDelete: () => {
        let ids = $('.check-item:checked').map(function () { return $(this).val(); }).get();
        if (ids.length === 0) {
            message.sweetError('Informasi', 'Pilih minimal satu data untuk dihapus.');
            return;
        }

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Hapus ${ids.length} data terpilih?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url.base_url(RolePermission.moduleApi()) + 'delete-bulk',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + Token.get() },
                    data: { ids: ids },
                    success: function (res) {
                        Swal.fire('Terhapus!', res.message || 'Data berhasil dihapus.', 'success');
                        RolePermission.getData();
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
    }
};

$(function () {
    if ($('#data-table').length) {
        RolePermission.getData();
    }
    if ($('.select2').length) {
        $('.select2').select2({ width: '100%' });
    }
    if ($('#can_all').length) {
        RolePermission.bindSelectAll();
    }
});
