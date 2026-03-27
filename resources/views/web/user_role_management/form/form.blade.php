@canAccess('master/user-roles','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">FORM {{ $data_page['title'] }}</h4>
            </div>
            <div class="card-body border-top"><input id="id" type="hidden" value="{{ $data->id ?? '' }}" />
                <div class="row">
                    <div class="col-md-6 mb-3"><label>User</label><select id="user_id"
                            class="form-control select2 required" error="User">
                            <option value="">Pilih</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}"
                                    {{ isset($data->user_id) && $data->user_id == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}</option>
                            @endforeach
                        </select></div>
                    <div class="col-md-6 mb-3"><label>Role</label><select id="role_id"
                            class="form-control select2 required" error="Role">
                            <option value="">Pilih</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->id }}"
                                    {{ isset($data->role_id) && $data->role_id == $r->id ? 'selected' : '' }}>
                                    {{ $r->role_name }}
                                </option>
                            @endforeach
                        </select></div>
                </div>
            </div>
            <div class="card-footer text-end">
                @if ($data_page['action'] != 'detail')
                    <button class="btn btn-primary" onclick="UserRoleManagement.submit()">Save</button>
                @endif
                <button class="btn btn-secondary" onclick="UserRoleManagement.back()">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endcanAccess
