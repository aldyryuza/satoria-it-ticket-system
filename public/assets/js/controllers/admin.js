var Admin = {
    table: null,
    selectedStatus: '',
    init: function () {
        this.initDataTable();
        this.bindEvents();
    },

    initDataTable: function () {
        var self = this;
        var currentPath = window.location.pathname;
        var tableConfig = {};

        if (currentPath.includes('tickets/all')) {
            tableConfig = {
                ajax: {
                    url: '/api/tickets/all/getData',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    },
                    data: function (d) {
                        d.status = self.selectedStatus;
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'ticket_number', name: 'ticket_number' },
                    { data: 'request_type', name: 'request_type' },
                    { data: 'title', name: 'title' },
                    {
                        data: 'status', name: 'status', render: function (data, type, row) {
                            let bg = '';
                            if (data == 'DRAFT') {
                                bg = 'bg-label-secondary';
                            } else if (data == 'ASSIGNED') {
                                bg = 'bg-label-warning';
                            } else if (data == 'done') {
                                bg = 'bg-label-success';
                            } else if (data == 'closed') {
                                bg = 'bg-label-dark';
                            } else if (data == 'REJECTED') {
                                bg = 'bg-label-danger';
                            } else if (data == 'WAITING APPROVAL') {
                                bg = 'bg-label-info';
                            } else if (data == 'in_progress') {
                                bg = 'bg-label-primary';
                            }
                            return '<span class="badge ' + bg + '">' + data + '</span>';
                        }
                    },
                    { data: 'requester_id', name: 'requester_id' },
                    { data: 'assigned_to', name: 'assigned_to' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            };
        } else if (currentPath.includes('waiting-assignment')) {
            tableConfig = {
                ajax: {
                    url: '/api/tickets/waiting-assignment/getData',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'ticket_number', name: 'ticket_number' },
                    { data: 'title', name: 'title' },
                    { data: 'requester_id', name: 'requester_id' },
                    { data: 'urgency_level', name: 'urgency_level' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            };
        } else if (currentPath.includes('in-progress')) {
            tableConfig = {
                ajax: {
                    url: '/api/tickets/in-progress/getData',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'ticket_number', name: 'ticket_number' },
                    { data: 'title', name: 'title' },
                    { data: 'requester_id', name: 'requester_id' },
                    { data: 'assigned_to', name: 'assigned_to' },
                    { data: 'urgency_level', name: 'urgency_level' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            };
        } else if (currentPath.includes('done')) {
            tableConfig = {
                ajax: {
                    url: '/api/tickets/done/getData',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'ticket_number', name: 'ticket_number' },
                    { data: 'title', name: 'title' },
                    { data: 'requester_id', name: 'requester_id' },
                    { data: 'assigned_to', name: 'assigned_to' },
                    { data: 'urgency_level', name: 'urgency_level' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            };
        } else if (currentPath.includes('closed')) {
            tableConfig = {
                ajax: {
                    url: '/api/tickets/closed/getData',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'ticket_number', name: 'ticket_number' },
                    { data: 'title', name: 'title' },
                    { data: 'status', name: 'status' },
                    { data: 'requester_id', name: 'requester_id' },
                    { data: 'assigned_to', name: 'assigned_to' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            };
        }

        if (Object.keys(tableConfig).length > 0) {
            this.table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: tableConfig.ajax,
                columns: tableConfig.columns,
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                language: {
                    processing: '<i class="bx bx-loader-alt bx-spin"></i> Loading...',
                    emptyTable: 'No data available'
                }
            });
        }
    },

    bindEvents: function () {
        var self = this;

        // Filter status - hanya aktif di halaman all tickets
        $(document).on('change', '#filter-status', function () {
            self.selectedStatus = $(this).val();
            if (self.table) {
                self.table.ajax.reload();
            }
        });

        // Reset filter
        $(document).on('click', '#btn-reset-filter', function () {
            self.selectedStatus = '';
            $('#filter-status').val('');
            if (self.table) {
                self.table.ajax.reload();
            }
        });

        // View ticket details
        $(document).on('click', '.btn-view', function () {
            let id = $(this).data('id');

            console.log('ID:', id); // debug

            if (!id) {
                toastr.error('Invalid ID');
                return;
            }

            self.view(id);
        });

        // Assign or Reassign ticket with modal
        $(document).on('click', '.btn-assign', function () {
            var id = $(this).data('id');
            var assignedTo = $(this).data('assigned');

            $('#assignForm').data('ticketId', id);
            $('#assignForm').find('select[name="assigned_to"]').val(assignedTo).trigger('change');
            $('#assignModal').modal('show');
        });

        // Close ticket
        $(document).on('click', '.btn-success', function () {
            var id = $(this).data('id');
            self.close(id);
        });

        // Assign form submit
        $('#assignForm').on('submit', function (e) {
            e.preventDefault();
            var id = $(this).data('ticketId');
            var assigned_to = $(this).find('select[name="assigned_to"]').val();
            var note = $(this).find('textarea[name="note"]').val();
            var plan_due_date = $(this).find('input[name="plan_due_date"]').val();

            if (!id || !assigned_to) {
                toastr.error('Please select worker to assign');
                return;
            }

            $.ajax({
                url: '/api/tickets/assign/' + id,
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                data: {
                    assigned_to: assigned_to,
                    note: note,
                    plan_due_date: plan_due_date,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#assignModal').modal('hide');
                        self.reloadTable();
                    } else {
                        toastr.error(response.message || 'Assignment failed');
                    }
                },
                error: function () {
                    toastr.error('An error occurred while assigning ticket');
                }
            });
        });
    },

    view: function (id) {
        // Redirect to ticket detail view
        window.location.href = '/tickets/' + id;
    },

    assign: function (id) {
        // Redirect to assignment page
        window.location.href = '/tickets/assign/' + id;
    },

    close: function (id) {
        if (confirm('Are you sure you want to close this ticket?')) {
            $.ajax({
                url: '/api/tickets/close/' + id,
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Ticket closed successfully');
                        Admin.table.ajax.reload();
                    } else {
                        toastr.error('Failed to close ticket');
                    }
                },
                error: function () {
                    toastr.error('An error occurred');
                }
            });
        }
    },

    reloadTable: function () {
        if (this.table) {
            this.table.ajax.reload();
        }
    }
};

$(document).ready(function () {
    Admin.init();
});
