@canAccess('master/users','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">FORM {{ $data_page['title'] }}</h4>
            </div>
            <div class="card-body border-top"><input id="id" type="hidden" value="{{ $data->id ?? '' }}" />
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Company</label><select id="company_id"
                            class="form-control select2 required" error="Company">
                            <option value="">Pilih</option>
                            @foreach ($companies as $c)
                                <option value="{{ $c->id }}"
                                    {{ isset($data->company_id) && $data->company_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->company_name }}</option>
                            @endforeach
                        </select></div>
                    <div class="col-md-6 mb-3"><label>Division</label><select id="division_id"
                            class="form-control select2 required" error="Division">
                            <option value="">Pilih</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}"
                                    {{ isset($data->division_id) && $data->division_id == $d->id ? 'selected' : '' }}>
                                    {{ $d->division_name }}</option>
                            @endforeach
                        </select></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Name</label><input id="name" class="form-control required"
                            error="Name" value="{{ $data->name ?? '' }}" /></div>
                    <div class="col-md-6 mb-3"><label>Username</label><input id="username"
                            class="form-control required" error="Username" value="{{ $data->username ?? '' }}" /></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Email</label><input id="email" class="form-control required"
                            error="Email" value="{{ $data->email ?? '' }}" /></div>
                    <div class="col-md-6 mb-3"><label>Password</label><input id="password" type="password"
                            class="form-control" placeholder="Kosongkan jika tidak diubah" /></div>
                </div>
                <div class="form-check"><input id="is_active" class="form-check-input" type="checkbox"
                        {{ isset($data->is_active) && $data->is_active ? 'checked' : '' }}><label class="form-check-label"
                        for="is_active">Active</label></div>
            </div>
            <div class="card-footer text-end">
                @if ($data_page['action'] != 'detail')
                    <button class="btn btn-primary" onclick="UserManagement.submit()">Save</button>
                @endif
                <button class="btn btn-secondary" onclick="UserManagement.back()">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endcanAccess
