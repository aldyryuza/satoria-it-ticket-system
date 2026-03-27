@extends('layouts.app')

@section('title', 'Assign Ticket')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Assign Ticket</h4>
                </div>
                <div class="card-body">
                    @canAccess('admin/tickets', 'update')
                    <form id="assignForm" method="POST">
                        @csrf
                        <input type="hidden" name="can_update" value="1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ticket_number">Ticket Number</label>
                                    <input type="text" class="form-control" id="ticket_number"
                                        value="{{ $ticket->ticket_number }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" value="{{ $ticket->title }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="requester">Requester</label>
                                    <input type="text" class="form-control" id="requester"
                                        value="{{ $ticket->requester->name ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="assigned_to">Assign to IT Worker *</label>
                                    <select class="form-control" id="assigned_to" name="assigned_to" required>
                                        <option value="">Select Worker</option>
                                        @foreach($workers as $worker)
                                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="4"
                                readonly>{{ $ticket->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Assign Ticket</button>
                            <a href="{{ route('admin.tickets.waiting-assignment') }}"
                                class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                    @endcanAccess
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
    $('#assignForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const ticketId = {{ $ticket->id }};

        $.ajax({
            url: '/api/admin/tickets/assign/' + ticketId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Ticket assigned successfully');
                    window.location.href = '{{ route("admin.tickets.waiting-assignment") }}';
                } else {
                    toastr.error(response.message || 'Failed to assign ticket');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'An error occurred');
            }
        });
    });
});
</script>
@endsection
