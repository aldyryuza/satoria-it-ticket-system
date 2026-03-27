@extends('web.template.layout')

@section('content')
<div class="container">
    <h1>Approval Tickets</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Ticket Number</th>
                <th>Title</th>
                <th>Requester</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->requester->name }}</td>
                <td>
                    <button class="btn btn-success" data-toggle="modal"
                        data-target="#approveModal{{ $ticket->id }}">Approve</button>
                    <button class="btn btn-danger" data-toggle="modal"
                        data-target="#rejectModal{{ $ticket->id }}">Reject</button>
                </td>
            </tr>
            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal{{ $ticket->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('approval.approve', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Approve Ticket</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Approve</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $ticket->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('approval.reject', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Reject Ticket</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
