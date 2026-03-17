@canAccess('master/roles','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">FORM {{ $data_page['title'] }}</h4>
                <p class="card-subtitle">Please fill the form</p>
            </div>
            <div class="card-body border-top"><input type="hidden" id="id" value="{{ $data['id'] ?? '' }}" />
                <div class="mb-3"><label>Role Name</label><input class="form-control required" id="role_name"
                        error="Role Name" value="{{ $data->role_name ?? '' }}"></div>
                <div class="mb-3"><label>Description</label><textarea id="description"
                        class="form-control">{{ $data->description ?? '' }}</textarea></div>
            </div>
            <div class="card-footer text-end">@if ($data_page['action'] != 'detail')<button class="btn btn-primary"
                    type="button" onclick="RoleManagement.submit()">Save</button>@endif<button class="btn btn-secondary"
                    type="button" onclick="RoleManagement.back()">Cancel</button></div>
        </div>
    </div>
</div>
@endcanAccess
