var Worker = {
    table: null,
    init: function () {
        this.initDataTable();
        this.bindEvents();
    },

    initDataTable: function () {
        var self = this;
        var currentPath = window.location.pathname;
        var tableConfig = {};

        if (currentPath.includes('tickets/my')) {
            tableConfig = {
                ajax: {
                    url: '/api/worker/tickets/my-tickets/getData',
                    type: 'POST',
                    data: function (d) {
                        d.status_filter = $('#status-filter').val();
                    },
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'ticket_number', name: 'ticket_number' },
                    { data: 'request_type', name: 'request_type' },
                    { data: 'title', name: 'title' },
                    { data: 'status', name: 'status' },
                    { data: 'requester_id', name: 'requester_id' },
                    { data: 'urgency_level', name: 'urgency_level' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            };
        } else if (currentPath.includes('history')) {
            tableConfig = {
                ajax: {
                    url: '/api/worker/tickets/history/getData',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'ticket_number', name: 'ticket_number' },
                    { data: 'request_type', name: 'request_type' },
                    { data: 'title', name: 'title' },
                    { data: 'status', name: 'status' },
                    { data: 'requester_id', name: 'requester_id' },
                    { data: 'urgency_level', name: 'urgency_level' },
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

        // View ticket details
        $(document).on('click', '.btn-info', function () {
            var id = $(this).data('id');
            self.view(id);
        });

        // Start work
        $(document).on('click', '.btn-primary', function () {
            var id = $(this).data('id');
            self.startWork(id);
        });

        // Update progress
        $(document).on('click', '.btn-warning', function () {
            var id = $(this).data('id');
            self.updateProgress(id);
        });

        // Mark done
        $(document).on('click', '.btn-success', function () {
            var id = $(this).data('id');
            self.markDone(id);
        });

        // Submit progress form
        $(document).on('submit', '#progressForm', function (e) {
            e.preventDefault();
            self.submitProgress();
        });

        // Filter by status
        $(document).on('change', '#status-filter', function () {
            if (self.table) {
                self.table.ajax.reload();
            }
        });
    },

    view: function (id) {
        // Redirect to ticket detail view
        window.location.href = '/tickets/' + id;
    },

    startWork: function (id) {
        if (confirm('Are you sure you want to start working on this ticket?')) {
            $.ajax({
                url: '/api/worker/tickets/start-work/' + id,
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Work started successfully');
                        Worker.table.ajax.reload();
                    } else {
                        toastr.error('Failed to start work');
                    }
                },
                error: function () {
                    toastr.error('An error occurred');
                }
            });
        }
    },

    markDone: function (id) {
        if (confirm('Are you sure you want to mark this ticket as done?')) {
            $.ajax({
                url: '/api/worker/tickets/mark-done/' + id,
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Ticket marked as done');
                        Worker.table.ajax.reload();
                    } else {
                        toastr.error('Failed to mark ticket as done');
                    }
                },
                error: function () {
                    toastr.error('An error occurred');
                }
            });
        }
    },

    updateProgress: function (id) {
        // Set the form action URL
        $('#progressForm').attr('action', '/worker/update-progress/' + id);
        // Reset form
        $('#progressForm')[0].reset();
        // Show modal
        $('#progressModal').modal('show');
    },

    submitProgress: function () {
        var form = $('#progressForm');
        var formData = new FormData(form[0]);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Authorization': 'Bearer ' + Token.get()
            },
            success: function (response) {
                $('#progressModal').modal('hide');
                toastr.success('Progress updated successfully');
                Worker.table.ajax.reload();
            },
            error: function (xhr) {
                var errorMessage = 'An error occurred while updating progress';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            }
        });
    },

    reloadTable: function () {
        if (this.table) {
            this.table.ajax.reload();
        }
    }
};

$(document).ready(function () {
    Worker.init();
});
