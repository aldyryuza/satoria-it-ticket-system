@canAccess('master/roles-permission','view')
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-body pb-0">
                <h4 class="card-title text-uppercase">FORM {{ $data_page['title'] }}</h4>
                <p class="card-subtitle mb-3">Plese fill the form</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <input type="hidden" name="id" id="id" value="{{ $data['id'] ?? '' }}" />
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select id="role_id" class="form-control required" error="Role">
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ isset($data->role_id) && $data->role_id == $role->id
                                    ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="menu_id" class="form-label">Menu</label>
                            <select id="menu_id" class="form-control required" error="Menu">
                                <option value="">Pilih Menu</option>
                                @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}" {{ isset($data->menu_id) && $data->menu_id == $menu->id
                                    ? 'selected' : '' }}>{{ $menu->menu_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="row mb-2">
                            <div class="col-sm-12 col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="can_all">
                                    <label class="form-check-label" for="can_all"><strong>Pilih Semua</strong></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input check-permission" type="checkbox" id="can_view" {{
                                        isset($data->can_view) && $data->can_view ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_view">Can View</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input check-permission" type="checkbox" id="can_create" {{
                                        isset($data->can_create) && $data->can_create ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_create">Can Create</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input check-permission" type="checkbox" id="can_update" {{
                                        isset($data->can_update) && $data->can_update ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_update">Can Update</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input check-permission" type="checkbox" id="can_delete" {{
                                        isset($data->can_delete) && $data->can_delete ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_delete">Can Delete</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input check-permission" type="checkbox" id="can_print" {{
                                        isset($data->can_print) && $data->can_print ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_print">Can Print</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 border-top text-end">
                @if ($data_page['action'] != 'detail')
                <button type="button" class="btn btn-primary" onclick="RolePermission.submit()">Save</button>
                @endif
                <button type="button" class="btn bg-danger-subtle text-danger ms-6 px-4"
                    onclick="RolePermission.back()">Cancel</button>
            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess
