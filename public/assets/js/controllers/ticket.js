// make a global variable const update dan const delete
const update = $('#update').val() ?? 0;
const del = $('#delete').val() ?? 0;

let Ticket = {
    module: () => 'tickets',
    moduleApi: () => 'api/' + Ticket.module(),

    create: () => {
        let _url = url.base_url(Ticket.module()) + 'create';
        window.location.href = _url;
    },
    back: () => {
        let _url = url.base_url(Ticket.module()) + 'history';
        window.location.href = _url;
    },
    view: (id) => {
        let _url = url.base_url(Ticket.module()) + id;
        window.location.href = _url;
    },
    edit: (id) => {
        let _url = url.base_url(Ticket.module()) + id + '/edit';
        window.location.href = _url;
    },
    submitForApproval: (id) => {
        if (confirm('Are you sure you want to submit this ticket for approval?')) {
            $.ajax({
                url: url.base_url(Ticket.moduleApi()) + id + '/submit-for-approval',
                method: 'POST',
                headers: {
                    // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    'Authorization': 'Bearer ' + Token.get()
                },
                success: function (response) {
                    if (response.success) {
                        message.sweetSuccess('Ticket submitted for approval successfully');
                        // Ticket.getData(); // Refresh table
                        window.location.href = url.base_url(Ticket.module()) + 'history';
                    } else {
                        message.sweetError(response.message || 'Failed to submit ticket');
                    }
                },
                error: function (xhr) {
                    message.sweetError('Failed to submit ticket for approval');
                }
            });
        }
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
                url: url.base_url(Ticket.moduleApi()) + 'getData',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get(),
                },
                data: function (d) {
                    d.status = $('#filter_status').val();
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
                { data: 'request_type' },
                { data: 'urgency_level' },
                { data: 'status' },
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
        Ticket.getData();
    }

    $('#filter_status').change(function () {
        Ticket.getData();
    });

    $('#request_type').change(function () {
        var type = $(this).val();

        $.ajax({
            url: '/api/ticket-fields/' + type,
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + Token.get(),
            },
            success: function (data) {
                $('#dynamic_fields').html(data);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});
