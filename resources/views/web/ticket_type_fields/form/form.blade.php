@canAccess('tickets/type-fields','view')
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-body pb-0">
                <h4 class="card-title text-uppercase">FORM {{ $data_page['title'] }}</h4>
                <p class="card-subtitle mb-3">Please fill the form</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <input type="hidden" id="id" value="{{ $data['id'] ?? '' }}" />
                    <div class="col-sm-6 col-md-4 mb-3">
                        <label for="ticket_type_id" class="form-label">Ticket Type ID</label>
                        <input type="text" id="ticket_type_id" class="form-control required" error="Ticket Type ID"
                            value="{{ $data->ticket_type_id ?? '' }}" placeholder="ACTION_APPS (Ex: CREATE_ZOOM)" />
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3">
                        <label for="field_name" class="form-label">Field Name</label>
                        <input type="text" id="field_name" class="form-control required" error="Field Name"
                            value="{{ $data->field_name ?? '' }}" placeholder="Ex: start_date" />
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3">
                        <label for="field_label" class="form-label">Field Label</label>
                        <input type="text" id="field_label" class="form-control required" error="Field Label"
                            value="{{ $data->field_label ?? '' }}" placeholder="Ex: Start Date" />
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3">
                        <label for="field_type" class="form-label">Field Type</label>
                        <select id="field_type" class="form-control select2 required" error="Field Type">
                            <option value="">Pilih Field Type</option>
                            <option value="text"
                                {{ isset($data->field_type) && $data->field_type == 'text' ? 'selected' : '' }}>
                                text</option>
                            <option value="textarea"
                                {{ isset($data->field_type) && $data->field_type == 'textarea' ? 'selected' : '' }}>
                                textarea</option>
                            <option value="date"
                                {{ isset($data->field_type) && $data->field_type == 'date' ? 'selected' : '' }}>
                                date</option>
                            <option value="number"
                                {{ isset($data->field_type) && $data->field_type == 'number' ? 'selected' : '' }}>
                                number</option>
                            <option value="time"
                                {{ isset($data->field_type) && $data->field_type == 'time' ? 'selected' : '' }}>
                                time</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3">
                        <label class="form-label">Required</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_required"
                                {{ isset($data->is_required) && $data->is_required ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_required">Yes</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 border-top text-end">
                @if ($data_page['action'] != 'detail')
                    <button class="btn btn-primary" type="button" onclick="TicketTypeField.submit()">Save</button>
                @endif
                <button class="btn bg-danger-subtle text-danger ms-6 px-4" type="button"
                    onclick="TicketTypeField.back()">Cancel</button>
            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess
