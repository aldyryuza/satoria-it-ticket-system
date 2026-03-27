@extends('web.template.layout')

@section('content')
<div class="container">
    <h1>My Ticket Requests</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Ticket Number</th>
                <th>Title</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->status }}</td>
                <td>{{ $ticket->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
