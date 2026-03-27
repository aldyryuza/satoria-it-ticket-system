// make a global variable const update dan const delete
const update = $('#update').val() ?? 0;
const del = $('#delete').val() ?? 0;

let Ticket = {
    module: () => 'tickets',
    moduleApi: () => 'api/' + Ticket.module()
}

let Approval = {
    module: () => 'tickets/approval',
    moduleApi: () => 'api/approvals',

    approve: (id) => {
        $('#approveForm').attr('action', url.base_url('approval/approve/' + id));
        $('#approveModal').modal('show');
    },
    reject: (id) => {
        $('#rejectForm').attr('action', url.base_url('approval/reject/' + id));
        $('#rejectModal').modal('show');
    },

    view: (id) => {
        let _url = url.base_url(Ticket.module()) + id;
        window.location.href = _url;
    },
    showApproveModal: (id) => {
        $('#ticketApproveForm').attr('action', url.base_url('approval/approve/' + id));
        $('#ticketApproveModal').modal('show');
    },
    showRejectModal: (id) => {
        $('#ticketRejectForm').attr('action', url.base_url('approval/reject/' + id));
        $('#ticketRejectModal').modal('show');
    },
    getData: () => {
        // hancurkan datatable lama biar tidak conflict
        if ($.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable().destroy();
        }

        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            autoWidth: false,
            destroy: true,
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            aLengthMenu: [
                [25, 50, 100],
                [25, 50, 100]
            ],
            ajax: {
                url: url.base_url(Approval.moduleApi()) + 'getData',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get(),
                },
                dataSrc: function (json) {
                    if (!json.data) {
                        console.error("Response tidak valid:", json);
                        return [];
                    }
                    return json.data;
                },
                error: function (xhr) {
                    console.error("Error DataTable:", xhr);
                    if (xhr.status === 401) {
                        alert('Token tidak valid atau sesi habis. Silakan login kembali.');
                        localStorage.removeItem('auth_token');
                        window.location.href = url.base_url('auth') + 'logout';
                    }
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'ticket_number' },
                { data: 'title' },
                { data: 'requester_id', name: 'requester.name' },
                { data: 'urgency_level' },
                { data: 'created_at' },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center align-middle'
                }
            ]
        });
    }
};

// Initialize when document is ready
$(document).ready(function () {
    if ($('#data-table').length) {
        Approval.getData();
    }
});
