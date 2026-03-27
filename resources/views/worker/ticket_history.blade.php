@extends('web.template.layout')

@section('content')
<div class="container">
    <h1>Ticket History: {{ $ticket->ticket_number }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $history)
            <tr>
                <td>{{ $history->user->name }}</td>
                <td>{{ $history->action }}</td>
                <td>{{ $history->description }}</td>
                <td>{{ $history->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
