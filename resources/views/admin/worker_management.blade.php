@extends('web.template.layout')

@section('content')
<div class="container">
    <h1>Worker Management</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workers as $worker)
            <tr>
                <td>{{ $worker->name }}</td>
                <td>{{ $worker->email }}</td>
                <td>
                    <!-- Add edit/delete actions if needed -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
