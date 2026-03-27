@extends('web.template.layout')

@section('content')
<div class="container">
    <h1>In Progress</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Ticket Number</th>
                <th>Title</th>
                <th>Requester</th>
                <th>Assigned To</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->requester->name }}</td>
                <td>{{ $ticket->worker->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
