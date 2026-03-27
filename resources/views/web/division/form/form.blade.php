@canAccess('master/departemen','view')
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-body pb-0">
                <h4 class="card-title text-uppercase">FORM {{ $data_page['title'] }}</h4>
                <p class="card-subtitle mb-3">Plese fill the form</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <input type="hidden" id="id" value="{{ $data['id'] ?? '' }}" />
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <select id="company_id" class="form-control select2 required" error="Company">
                            <option value="">Pilih Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ isset($data->company_id) && $data->company_id == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="division_name" class="form-label">Division Name</label>
                        <input type="text" id="division_name" class="form-control required" error="Division Name"
                            value="{{ $data->division_name ?? '' }}" />
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="head_user_id" class="form-label">Head User</label>
                        <select id="head_user_id" class="form-control select2">
                            <option value="">Pilih Head User (opsional)</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ isset($data->head_user_id) && $data->head_user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-3 border-top text-end">
                @if ($data_page['action'] != 'detail')
                    <button class="btn btn-primary" type="button" onclick="Division.submit()">Save</button>
                @endif
                <button class="btn bg-danger-subtle text-danger ms-6 px-4" type="button"
                    onclick="Division.back()">Cancel</button>
            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess
