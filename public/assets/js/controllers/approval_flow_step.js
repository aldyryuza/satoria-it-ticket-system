let ApprovalFlowStep = {
    module: () => 'setting/approval-flows',
    moduleApi: () => 'api/setting/approval-flows',

    add: () => {
        const flowId = $('#flow_id').val();
        window.location.href = url.base_url(ApprovalFlowStep.module()) + flowId + '/steps/create';
    },
    edit: (flowId, id) => {
        window.location.href = url.base_url(ApprovalFlowStep.module()) + flowId + '/steps/edit/' + id;
    },
    detail: (flowId, id) => {
        window.location.href = url.base_url(ApprovalFlowStep.module()) + flowId + '/steps/detail/' + id;
    },
    back: (flowId) => {
        window.location.href = url.base_url(ApprovalFlowStep.module()) + flowId + '/steps';
    },

    getData: () => {
        const flowId = $('#flow_id').val();
        if ($.fn.DataTable.isDataTable('#data-table')) { $('#data-table').DataTable().destroy(); }
        $('#data-table').DataTable({
            processing: true, serverSide: true, responsive: false, autoWidth: false, destroy: true,
            ajax: { url: url.base_url(ApprovalFlowStep.moduleApi()) + flowId + '/steps/getData', type: 'POST', headers: { 'Authorization': 'Bearer ' + Token.get() } },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'id', name: 'id' },
                { data: 'step_order', name: 'step_order' },
                { data: 'approver_role', name: 'approver_role' },
                { data: 'approver_user_name', name: 'approver_user_id' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'id', orderable: false, searchable: false, className: 'text-center align-middle', render: function (data, type, row) {
                        let btns = `<button class="btn btn-sm btn-info" onclick="ApprovalFlowStep.detail(${flowId}, ${row.id})"><i class="bx bx-detail"></i></button> `;
                        btns += `<button class="btn btn-sm btn-warning" onclick="ApprovalFlowStep.edit(${flowId}, ${row.id})"><i class="bx bx-edit"></i></button> `;
                        btns += `<button class="btn btn-sm btn-danger" onclick="ApprovalFlowStep.delete(${row.id})"><i class="bx bx-trash"></i></button>`;
                        return `<div class="d-flex justify-content-center gap-1">${btns}</div>`;
                    }
                }
            ]
        });
    },

    getPostData: () => ({
        id: $('#id').val(),
        flow_id: $('#flow_id').val(),
        step_order: $('#step_order').val(),
        approver_role: $('#approver_role').val(),
        approver_user_id: $('#approver_user_id').val(),
    }),

    submit: () => {
        if (validation.run() === 1) {
            $.ajax({
                type: 'POST', url: url.base_url(ApprovalFlowStep.moduleApi()) + '/steps/submit', data: ApprovalFlowStep.getPostData(), dataType: 'json', headers: { 'Authorization': 'Bearer ' + Token.get() }, beforeSend: () => message.loadingProses('Proses Simpan...'), success: function (response) {
                    message.closeLoading();
                    if (response.is_valid) {
                        message.sweetSuccess('Data berhasil disimpan');
                        ApprovalFlowStep.back($('#flow_id').val());
                    } else {
                        message.sweetError(response.message || 'Gagal simpan');
                    }
                }, error: function () { message.closeLoading(); message.sweetError('Terjadi kesalahan'); }
            });
        }
    },

    delete: (id) => {
        Swal.fire({ title: 'Konfirmasi', text: 'Hapus step ini?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal' }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url.base_url(ApprovalFlowStep.moduleApi()) + '/steps/delete/' + id, type: 'DELETE', headers: { 'Authorization': 'Bearer ' + Token.get() }, success: function () {
                        message.sweetSuccess('Terhapus');
                        ApprovalFlowStep.getData();
                    }, error: function () { message.sweetError('Gagal hapus'); }
                });
            }
        });
    }
};

$(function () {
    if ($('#data-table').length) {
        ApprovalFlowStep.getData();
    }
    if ($('.select2').length) {
        $('.select2').select2({ width: '100%' });
    }
});
