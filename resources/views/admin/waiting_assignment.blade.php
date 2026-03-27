@extends('web.template.layout')

@section('content')
<div class="container">
    <h1>Waiting Assignment</h1>
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
                    <button class="btn btn-primary" data-toggle="modal"
                        data-target="#assignModal{{ $ticket->id }}">Assign</button>
                </td>
            </tr>
            <!-- Assign Modal -->
            <div class="modal fade" id="assignModal{{ $ticket->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('admin.assign', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Ticket</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Assign To</label>
                                    <select name="assigned_to" class="form-control" required>
                                        <option value="">Select Worker</option>
                                        @foreach(\App\Models\User::whereHas('roles', function($q) { $q->where('name',
                                        'IT Worker'); })->get() as $worker)
                                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Plan Due Date</label>
                                    <input type="date" name="plan_due_date" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Assign</button>
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
