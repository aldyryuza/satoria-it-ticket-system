@extends('web.template.layout')

@section('content')
<div class="container">
    <h1>My Tickets</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Ticket Number</th>
                <th>Title</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->status }}</td>
                <td>
                    <button class="btn btn-info" data-toggle="modal"
                        data-target="#progressModal{{ $ticket->id }}">Update Progress</button>
                    <a href="{{ route('worker.history', $ticket->id) }}" class="btn btn-secondary">History</a>
                </td>
            </tr>
            <!-- Progress Modal -->
            <div class="modal fade" id="progressModal{{ $ticket->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('worker.update_progress', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Update Progress</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Percent Progress</label>
                                    <input type="number" name="percent_progress" class="form-control" min="0" max="100"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label>Progress Note</label>
                                    <textarea name="progress_note" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Update</button>
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
