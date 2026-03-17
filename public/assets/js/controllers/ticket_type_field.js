let TicketTypeField = {
    module: () => 'tickets/type-fields',
    moduleApi: () => 'api/tickets/type-fields',

    add: () => { window.location.href = url.base_url(TicketTypeField.module()) + 'create'; },
    edit: (id) => { window.location.href = url.base_url(TicketTypeField.module()) + 'edit/' + id; },
    detail: (id) => { window.location.href = url.base_url(TicketTypeField.module()) + 'detail/' + id; },
    back: () => { window.location.href = url.base_url(TicketTypeField.module()); },

    getData: () => {
        if ($.fn.DataTable.isDataTable('#data-table')) { $('#data-table').DataTable().destroy(); }
        $('#data-table').DataTable({
            processing: true, serverSide: true, responsive: false, autoWidth: false, destroy: true,
            ajax: { url: url.base_url(TicketTypeField.moduleApi()) + 'getData', type: 'POST', headers: { 'Authorization': 'Bearer ' + Token.get() } },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'id', name: 'id' },
                { data: 'ticket_type_id', name: 'ticket_type_id' },
                { data: 'field_name', name: 'field_name' },
                { data: 'field_label', name: 'field_label' },
                { data: 'field_type', name: 'field_type' },
                { data: 'is_required', name: 'is_required' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'id', orderable: false, searchable: false, className: 'text-center align-middle', render: function (data, type, row) {
                        let btns = `<button class="btn btn-sm btn-info" onclick="TicketTypeField.detail(${row.id})"><i class="bx bx-detail"></i></button> `;
                        btns += `<button class="btn btn-sm btn-warning" onclick="TicketTypeField.edit(${row.id})"><i class="bx bx-edit"></i></button> `;
                        btns += `<button class="btn btn-sm btn-danger" onclick="TicketTypeField.delete(this,event)" data-id="${row.id}"><i class="bx bx-trash"></i></button>`;
                        return `<div class="d-flex justify-content-center gap-1">${btns}</div>`;
                    }
                }
            ]
        });
    },

    getPostData: () => ({
        id: $('#id').val(),
        ticket_type_id: $('#ticket_type_id').val(),
        field_name: $('#field_name').val(),
        field_label: $('#field_label').val(),
        field_type: $('#field_type').val(),
        is_required: $('#is_required').is(':checked') ? 1 : 0,
    }),

    submit: () => {
        if (validation.run() === 1) {
            $.ajax({
                type: 'POST', url: url.base_url(TicketTypeField.moduleApi()) + 'submit', data: TicketTypeField.getPostData(), dataType: 'json', headers: { 'Authorization': 'Bearer ' + Token.get() }, beforeSend: () => message.loadingProses('Proses Simpan...'), success: function (response) {
                    message.closeLoading();
                    if (response.is_valid) { message.sweetSuccess('Data berhasil disimpan'); TicketTypeField.back(); } else { message.sweetError(response.message || 'Gagal simpan'); }
                }, error: function () { message.closeLoading(); message.sweetError('Terjadi kesalahan'); }
            });
        }
    },

    delete: (elm, e) => {
        let id = $(elm).data('id');
        Swal.fire({ title: 'Konfirmasi', text: 'Hapus data?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal' }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url.base_url(TicketTypeField.moduleApi()) + '/delete/' + id, type: 'DELETE', headers: { 'Authorization': 'Bearer ' + Token.get() }, success: function (res) { message.sweetSuccess('Terhapus'); TicketTypeField.getData(); }, error: function () { message.sweetError('Gagal menghapus'); } });
            }
        });
        return false;
    }
};

$(function () {
    if ($('#data-table').length) TicketTypeField.getData();
    if ($('.select2').length) $('.select2').select2({ width: '100%' });
});
