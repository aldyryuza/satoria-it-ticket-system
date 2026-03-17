@canAccess('master/menu','view')
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-body pb-0">
                <h4 class="card-title text-uppercase">FORM {{ $data_page['title'] }}</h4>
                <p class="card-subtitle mb-3">Plese fill the form</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <input type="text" name="id" id="id" value="{{ $data['id'] ?? null }}" hidden>
                            <label for="parent_menu" class="form-label">Parent Menu</label>
                            <br>
                            <select class="select2 form-control" name="parent_menu" id="parent_menu">
                                <option value="">Select Parent Menu</option>
                                @foreach ($data_menu as $item)
                                <option value="{{ $item->id }}" {{ isset($data['parent_id']) &&
                                    $data['parent_id']==$item->id ? 'selected' : '' }}>
                                    {{ $item->menu_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="menu_name" class="form-label">Menu Name</label>
                            <input type="text" class="form-control required" id="menu_name" error="Menu Name"
                                placeholder="Enter name" value="{{ $data['menu_name'] ?? null }}" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="route" class="form-label">Route</label>
                            <input type="text" class="form-control required" id="route" error="Route"
                                placeholder="<parent>/<child>" value="{{ $data['route'] ?? null }}" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon Menu</label>
                            <input type="text" class="form-control" id="icon" placeholder="card"
                                value="{{ $data['icon'] ?? null }}" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug Menu</label>
                            <input type="text" class="form-control" id="slug" placeholder="<parent>_<child>"
                                value="{{ $data['slug'] ?? null }}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 border-top">
                <div class="text-end">
                    @if ($data_page['action'] != 'detail')
                    <button type="button" class="btn btn-primary" onclick="Menu.submit()">
                        Save
                    </button>
                    @endif
                    <button type="button" class="btn bg-danger-subtle text-danger ms-6 px-4" onclick="Menu.back()">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess