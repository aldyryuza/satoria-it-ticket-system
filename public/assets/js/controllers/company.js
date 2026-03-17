const update_company = $('#update').val() ?? 0;
const delete_company = $('#delete').val() ?? 0;

let Company = {
    module: () => 'master/subsidiary',
    moduleApi: () => 'api/' + Company.module(),
    add: () => { window.location.href = url.base_url(Company.module()) + 'create'; },
    edit: (id) => { window.location.href = url.base_url(Company.module()) + 'edit/' + id; },
    detail: (id) => { window.location.href = url.base_url(Company.module()) + 'detail/' + id; },
    back: () => { window.location.href = url.base_url(Company.module()); },

    getData: () => {
        if ($.fn.DataTable.isDataTable('#data-table')) { $('#data-table').DataTable().destroy(); }
        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url.base_url(Company.moduleApi()) + 'getData',
                type: 'POST',
                headers: { 'Authorization': 'Bearer ' + Token.get() },
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'id' },
                { data: 'company_code' },
                { data: 'company_name' },
                { data: 'is_holding' },
                { data: 'created_at' },
                {
                    data: 'action', orderable: false, searchable: false,
                    render: function (data, type, row) {
                        let btns = '';
                        if (update_company == 1) {
                            btns += `<button class="btn btn-sm btn-info" onclick="Company.detail(${row.id})"><i class="bx bx-detail"></i></button> `;
                            btns += `<button class="btn btn-sm btn-warning" onclick="Company.edit(${row.id})"><i class="bx bx-edit"></i></button> `;
                        }
                        if (delete_company == 1) {
                            btns += `<button class="btn btn-sm btn-danger" data-id="${row.id}" onclick="Company.delete(this,event)"><i class="bx bx-trash"></i></button>`;
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
            company_code: $('#company_code').val(),
            company_name: $('#company_name').val(),
            is_holding: $('#is_holding').is(':checked') ? 1 : 0,
        };
    },

    submit: () => {
        let params = Company.getPostData();
        let _url = url.base_url(Company.moduleApi()) + 'submit';
        if (validation.run() === 1) {
            $.ajax({
                type: 'POST', url: _url, data: params, dataType: 'json',
                headers: { 'Authorization': 'Bearer ' + Token.get() },
                beforeSend: () => { message.loadingProses('Proses Simpan...'); },
                success: function (res) {
                    message.closeLoading();
                    if (res.is_valid) { message.sweetSuccess(res.message); Company.back(); }
                    else { message.sweetError(res.message); }
                }
            });
        }
    },

    delete: (elm, e) => {
        let id = $(elm).data('id');
        let _url = url.base_url(Company.moduleApi()) + 'delete/' + id;
        Swal.fire({
            title: 'Apakah Anda yakin?', text: 'Data ini akan dihapus!', icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: _url, type: 'DELETE', headers: { 'Authorization': 'Bearer ' + Token.get() },
                    success: function (res) { Swal.fire('Terhapus!', res.message || 'Data dihapus', 'success'); Company.getData(); },
                    error: function (xhr) { Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error'); }
                });
            }
        });
        return false;
    }
};

$(function () {
    if ($('#data-table').length) {
        Company.getData();
    }
});
