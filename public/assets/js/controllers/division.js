const update_division = $('#update').val() ?? 0;
const delete_division = $('#delete').val() ?? 0;

let Division = {
    module: () => 'master/departemen',
    moduleApi: () => 'api/' + Division.module(),

    add: () => { window.location.href = url.base_url(Division.module()) + 'create'; },
    edit: (id) => { window.location.href = url.base_url(Division.module()) + 'edit/' + id; },
    detail: (id) => { window.location.href = url.base_url(Division.module()) + 'detail/' + id; },
    back: () => { window.location.href = url.base_url(Division.module()); },

    getData: () => {
        if ($.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable().destroy();
        }
        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url.base_url(Division.moduleApi()) + 'getData',
                type: 'POST',
                headers: { 'Authorization': 'Bearer ' + Token.get() }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'id' },
                { data: 'company_name' },
                { data: 'division_name' },
                { data: 'head_user_id' },
                { data: 'created_at' },
                {
                    data: 'action', orderable: false, searchable: false,
                    render: function (data, type, row) {
                        let btns = '';
                        if (update_division == 1) {
                            btns += `<button class="btn btn-sm btn-info" onclick="Division.detail(${row.id})"><i class="bx bx-detail"></i></button> `;
                            btns += `<button class="btn btn-sm btn-warning" onclick="Division.edit(${row.id})"><i class="bx bx-edit"></i></button> `;
                        }
                        if (delete_division == 1) {
                            btns += `<button class="btn btn-sm btn-danger" data-id="${row.id}" onclick="Division.delete(this,event)"><i class="bx bx-trash"></i></button>`;
                        }
                        return `<div class="d-flex justify-content-center gap-1">${btns}</div>`;
                    }
                }
            ]
        });
    },

    getPostData: () => {
        return {
            id: $('#id').val(),
            company_id: $('#company_id').val(),
            division_name: $('#division_name').val(),
            head_user_id: $('#head_user_id').val(),
        };
    },

    submit: () => {
        let params = Division.getPostData();
        let _url = url.base_url(Division.moduleApi()) + 'submit';
        if (validation.run() === 1) {
            $.ajax({
                type: 'POST', url: _url, data: params, dataType: 'json',
                headers: { 'Authorization': 'Bearer ' + Token.get() },
                beforeSend: () => { message.loadingProses('Proses Simpan...'); },
                success: function (res) {
                    message.closeLoading();
                    if (res.is_valid) { message.sweetSuccess(res.message); Division.back(); }
                    else { message.sweetError(res.message); }
                }
            });
        }
    },

    delete: (elm, e) => {
        let id = $(elm).data('id');
        let _url = url.base_url(Division.moduleApi()) + 'delete/' + id;
        Swal.fire({
            title: 'Apakah Anda yakin?', text: 'Data ini akan dihapus!', icon: 'warning',
            showCancelButton: true, confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: _url, type: 'DELETE', dataType: 'json', headers: { 'Authorization': 'Bearer ' + Token.get() },
                    success: function (res) { Swal.fire('Terhapus!', res.message || 'Data dihapus.', 'success'); Division.getData(); },
                    error: function (xhr) { Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error'); }
                });
            }
        });
        return false;
    }
};

$(function () {
    if ($('#data-table').length) {
        Division.getData();
    }
    $('.select2').select2({ width: '100%' });
});
