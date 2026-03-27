@canAccess('create_tickets','view')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Ticket Details Card -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">{{ $ticket->ticket_number }} - {{ $ticket->title }}</h4>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span
                                class="badge badge-{{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                            @if ($ticket->status == 'DRAFT' && session('id') == $ticket->requester_id)
                                <button class="btn btn-sm btn-success" title="Submit for Approval"
                                    onclick="Ticket.submitForApproval({{ $ticket->id }})"><i class="bx bx-send"></i>
                                    Submit for Approval</button>
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-primary"
                                    title="Edit Ticket">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <form action="{{ route('tickets.delete', $ticket->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this ticket?')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            @endif

                            @if ($ticket->status == 'WAITING APPROVAL' && session('id') == $ticket->current_approver)
                                <button class="btn btn-sm btn-success"
                                    onclick="Approval.showApproveModal({{ $ticket->id }})">
                                    <i class="bx bx-check"></i> Approve
                                </button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="Approval.showRejectModal({{ $ticket->id }})">
                                    <i class="bx bx-x"></i> Reject
                                </button>
                            @endif

                            @if ($ticket->status == 'done' && in_array(session('role_name'), ['it_admin', 'super_admin']))
                                <button class="btn btn-sm btn-danger" id="btn-close-ticket" title="Close Ticket"
                                    onclick="closeTicket({{ $ticket->id }})">
                                    <i class="bx bx-lock-alt"></i> Close Ticket
                                </button>
                            @endif

                            {{-- <button class="btn btn-sm btn-secondary" title="Back to List" onclick="Ticket.back()">
                                <i class="bx bx-arrow-back"></i> Back
                            </button> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Requester:</strong> {{ $ticket->requester->name ?? 'N/A' }}</p>
                            <p><strong>Company:</strong> {{ $ticket->company->company_name ?? 'N/A' }}</p>
                            <p><strong>Division:</strong> {{ $ticket->division->division_name ?? 'N/A' }}</p>
                            <p><strong>Request Type:</strong> {{ $ticket->request_type }}</p>
                            <p><strong>Urgency Level:</strong> {{ $ticket->urgency_level }}</p>

                        </div>
                        <div class="col-md-6">

                            <p><strong>Assigned to:</strong> {{ $ticket->worker->name ?? 'Not Assigned' }}</p>
                            <p><strong>Created at:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            @if ($ticket->plan_due_date)
                                <p><strong>Plan Due Date:</strong> {{ $ticket->plan_due_date }}
                                </p>
                            @endif
                            @if ($ticket->done_at)
                                <p><strong>Done at:</strong> {{ $ticket->done_at }}</p>
                            @endif
                            @if ($ticket->closed_at)
                                <p><strong>Closed at:</strong> {{ $ticket->closed_at }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            @foreach ($fields as $item)
                                {{-- make a table --}}
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="width: 20%;">{{ ucwords(str_replace('_', ' ', $item->field_name)) }}
                                        </td>
                                        <td style="width: 10%;">:</td>
                                        {{-- text-left --}}
                                        <td style="width: 70%;" class="text-left">{{ $item->field_value }}</td>
                                    </tr>
                                </table>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6><strong>Description:</strong></h6>
                            <p>{{ $ticket->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Comments</h5>
                </div>
                <div class="card-body">
                    @canAccess('create_tickets', 'create')
                    <form id="commentForm" class="mb-3">
                        @csrf
                        <textarea name="comment" class="form-control" rows="3" placeholder="Add a comment..." required></textarea>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">Post Comment</button>
                        <input type="hidden" name="can_create_comment" value="1">
                    </form>
                    @endcanAccess

                    <div id="commentsList">
                        <!-- Comments will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Attachments Component -->
            @include('components.attachments')

            <!-- Approval action modals -->
            @if ($ticket->status == 'WAITING APPROVAL' && session('id') == $ticket->current_approver)
                <!-- APPROVE MODAL -->
                <div class="modal fade" id="ticketApproveModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="ticketApproveLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <form id="ticketApproveForm" method="POST"
                                action="{{ url('/approval/approve/' . $ticket->id) }}">
                                @csrf
                                <div class="modal-header d-flex align-items-center">
                                    <h4 class="modal-title" id="ticketApproveLabel">
                                        Approve Ticket
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea name="note" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn bg-danger-subtle text-danger waves-effect"
                                        data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn bg-success-subtle text-success waves-effect">
                                        Approve
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- REJECT MODAL -->
                <div class="modal fade" id="ticketRejectModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="ticketRejectLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <form id="ticketRejectForm" method="POST"
                                action="{{ url('/approval/reject/' . $ticket->id) }}">
                                @csrf
                                <div class="modal-header d-flex align-items-center">
                                    <h4 class="modal-title" id="ticketRejectLabel">
                                        Reject Ticket
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea name="note" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn bg-danger-subtle text-danger waves-effect"
                                        data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn bg-danger-subtle text-danger waves-effect">
                                        Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- History Component -->
            @include('components.history')
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess


@section('scripts')
    <script src="{{ asset('assets/js/controllers/attachment.js') }}"></script>
    <script src="{{ asset('assets/js/controllers/history.js') }}"></script>
    <script>
        $(document).ready(function() {
            var ticketId = {{ $ticket->id }};

            // Initialize attachments
            Attachment.init(ticketId);

            // Initialize history
            TicketHistory.init(ticketId);

            // Handle comment form
            $('#commentForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: '/api/tickets/' + ticketId + '/comments',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    },
                    success: function(response) {
                        if (response.success) {
                            message.sweetSuccess('Comment posted successfully');
                            $('#commentForm')[0].reset();
                            loadComments();
                        } else {
                            message.sweetError(response.message || 'Failed to post comment');
                        }
                    },
                    error: function() {
                        message.sweetError('An error occurred');
                    }
                });
            });

            // Load comments on page load
            function loadComments() {
                $.ajax({
                    url: '/api/tickets/' + ticketId + '/comments',
                    type: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    },
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            var html = '';
                            response.data.forEach(function(comment) {
                                html += '<div class="comment-item mb-3">' +
                                    '<strong>' + comment.user.name + '</strong>' +
                                    '<p class="text-muted">' + comment.comment + '</p>' +
                                    '<small class="text-muted">' + comment.created_at +
                                    '</small>' +
                                    '</div>';
                            });
                            $('#commentsList').html(html);
                        } else {
                            $('#commentsList').html('<p class="text-muted">No comments yet</p>');
                        }
                    }
                });
            }

            loadComments();

            // Close ticket function
            window.closeTicket = function(id) {
                if (!confirm('Are you sure you want to close this ticket? This action cannot be undone.')) {
                    return;
                }

                $('#btn-close-ticket').prop('disabled', true).html(
                    '<i class="bx bx-loader-alt bx-spin"></i> Closing...');

                $.ajax({
                    url: '/api/tickets/close/' + id,
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    },
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Ticket closed successfully');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1200);
                        } else {
                            toastr.error(response.message || 'Failed to close ticket');
                            $('#btn-close-ticket').prop('disabled', false).html(
                                '<i class="bx bx-lock-alt"></i> Close Ticket');
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while closing the ticket');
                        $('#btn-close-ticket').prop('disabled', false).html(
                            '<i class="bx bx-lock-alt"></i> Close Ticket');
                    }
                });
            };
        });
    </script>

    @if ($ticket->status == 'WAITING APPROVAL' && session('id') == $ticket->current_approver)
        <script>
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
                            dataSrc: function(json) {
                                if (!json.data) {
                                    console.error("Response tidak valid:", json);
                                    return [];
                                }
                                return json.data;
                            },
                            error: function(xhr) {
                                console.error("Error DataTable:", xhr);
                                if (xhr.status === 401) {
                                    alert('Token tidak valid atau sesi habis. Silakan login kembali.');
                                    localStorage.removeItem('auth_token');
                                    window.location.href = url.base_url('auth') + 'logout';
                                }
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'ticket_number'
                            },
                            {
                                data: 'title'
                            },
                            {
                                data: 'requester_id',
                                name: 'requester.name'
                            },
                            {
                                data: 'urgency_level'
                            },
                            {
                                data: 'created_at'
                            },
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
        </script>
    @endif
@endsection
