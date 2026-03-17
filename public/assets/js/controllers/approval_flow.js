let ApprovalFlow = {
    module: () => 'setting/approval-flows',
    moduleApi: () => 'api/setting/approval-flows',

    add: () => { window.location.href = url.base_url(ApprovalFlow.module()) + 'create'; },
    edit: (id) => { window.location.href = url.base_url(ApprovalFlow.module()) + 'edit/' + id; },
    detail: (id) => { window.location.href = url.base_url(ApprovalFlow.module()) + 'detail/' + id; },
    back: () => { window.location.href = url.base_url(ApprovalFlow.module()); },
    steps: (id) => { window.location.href = url.base_url(ApprovalFlow.module()) + id + '/steps'; },

    getData: () => {
        if ($.fn.DataTable.isDataTable('#data-table')) { $('#data-table').DataTable().destroy(); }
        $('#data-table').DataTable({
            processing: true, serverSide: true, responsive: false, autoWidth: false, destroy: true,
            ajax: { url: url.base_url(ApprovalFlow.moduleApi()) + 'getData', type: 'POST', headers: { 'Authorization': 'Bearer ' + Token.get() } },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'id', name: 'id' },
                { data: 'company_name', name: 'company_id' },
                { data: 'division_name', name: 'division_id' },
                { data: 'flow_name', name: 'flow_name' },
                { data: 'is_active', name: 'is_active' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'id', orderable: false, searchable: false, className: 'text-center align-middle', render: function (data, type, row) {
                        let btns = '';
                        if (window.updatemode === '1' || $('#update').val() == '1') {
                            btns += `<button class="btn btn-sm btn-info" onclick="ApprovalFlow.detail(${row.id})"><i class="bx bx-detail"></i></button> `;
                            btns += `<button class="btn btn-sm btn-warning" onclick="ApprovalFlow.edit(${row.id})"><i class="bx bx-edit"></i></button> `;
                        }
                        btns += `<button class="btn btn-sm btn-secondary" onclick="ApprovalFlow.steps(${row.id})"><i class="bx bx-list-ul"></i></button> `;
                        if (window.delmode === '1' || $('#delete').val() == '1') {
                            btns += `<button class="btn btn-sm btn-danger" onclick="ApprovalFlow.delete(this,event)" data-id="${row.id}"><i class="bx bx-trash"></i></button>`;
                        }
                        return `<div class="d-flex justify-content-center gap-1">${btns}</div>`;
                    }
                }
            ]
        });
    },

    getPostData: () => ({
        id: $('#id').val(),
        company_id: $('#company_id').val(),
        division_id: $('#division_id').val(),
        flow_name: $('#flow_name').val(),
        is_active: $('#is_active').is(':checked') ? 1 : 0,
    }),

    submit: () => {
        if (validation.run() === 1) {
            $.ajax({
                type: 'POST', url: url.base_url(ApprovalFlow.moduleApi()) + '/submit', data: ApprovalFlow.getPostData(), dataType: 'json', headers: { 'Authorization': 'Bearer ' + Token.get() }, beforeSend: () => { message.loadingProses('Proses Simpan...'); }, success: function (response) {
                    message.closeLoading();
                    if (response.is_valid) {
                        message.sweetSuccess('Data berhasil disimpan');
                        ApprovalFlow.back();
                    } else message.sweetError(response.message || 'Gagal menyimpan');
                }, error: function (xhr) { message.closeLoading(); message.sweetError('Terjadi kesalahan'); }
            });
        }
    },

    delete: (elm, e) => {
        let id = $(elm).data('id');
        Swal.fire({ title: 'Konfirmasi', text: 'Hapus data?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal' }).then((res) => {
            if (res.isConfirmed) {
                $.ajax({ url: url.base_url(ApprovalFlow.moduleApi()) + '/delete/' + id, type: 'DELETE', headers: { 'Authorization': 'Bearer ' + Token.get() }, success: function () { message.sweetSuccess('Terhapus'); ApprovalFlow.getData(); }, error: function () { message.sweetError('Gagal menghapus'); } });
            }
        });
    }
};

$(function () { if ($('#data-table').length) ApprovalFlow.getData(); if ($('.select2').length) $('.select2').select2({ width: '100%' }); });
