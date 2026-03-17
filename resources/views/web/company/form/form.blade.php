@canAccess('master/subsidiary','view')
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
                        <label for="company_code" class="form-label">Company Code</label>
                        <input type="text" id="company_code" class="form-control required" error="Company Code"
                            value="{{ $data->company_code ?? '' }}" />
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" id="company_name" class="form-control required" error="Company Name"
                            value="{{ $data->company_name ?? '' }}" />
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_holding" {{ isset($data->is_holding)
                            && $data->is_holding ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_holding">Is Holding</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 border-top text-end">
                @if ($data_page['action'] != 'detail')
                <button class="btn btn-primary" type="button" onclick="Company.submit()">Save</button>
                @endif
                <button class="btn bg-danger-subtle text-danger ms-6 px-4" type="button"
                    onclick="Company.back()">Cancel</button>
            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess