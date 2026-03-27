@extends('web.template.layout')

@section('content')
<div class="container">
    <h1>Create Ticket</h1>
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Company</label>
            <select name="company_id" class="form-control" required>
                <option value="">Select Company</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Division</label>
            <select name="division_id" class="form-control" required>
                <option value="">Select Division</option>
                @foreach($divisions as $division)
                <option value="{{ $division->id }}">{{ $division->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Request Type</label>
            <select name="request_type" id="request_type" class="form-control" required>
                <option value="">Select Type</option>
                @foreach($ticketTypes as $type)
                <option value="{{ $type->request_type }}">{{ $type->request_type }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Urgency Level</label>
            <select name="urgency_level" class="form-control" required>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
        </div>
        <div id="dynamic_fields"></div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    $('#request_type').change(function() {
    var type = $(this).val();
    $.get('/api/ticket-fields/' + type, function(data) {
        $('#dynamic_fields').html(data);
    });
});
</script>
@endsection
