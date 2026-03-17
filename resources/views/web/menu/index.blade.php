@canAccess('master/menu','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    Welcome to the {{ $data_page['title'] }}!
                </p>

                @canAccess('master/menu','create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop">Test Modal</button>
                <button class="btn btn-primary" onclick="Menu.add()">
                    Tambah Data <i class="bx bx-plus"></i>
                </button>
                @endcanAccess
                @canAccess('dashboard','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess

                @canAccess('dashboard','delete')
                <input type="hidden" name="delete" id="delete" value="1">
                @endcanAccess

                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table id="data-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-upper">no</th>
                                    <th class="text-upper">id</th>
                                    <th class="text-upper">menu_name</th>
                                    <th class="text-upper">slug</th>
                                    <th class="text-upper">route</th>
                                    <th class="text-upper">parent_id</th>
                                    <th class="text-upper">icon</th>
                                    <th class="text-upper">order_number</th>
                                    <th class="text-upper">created at</th>
                                    <th class="text-upper">action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by Yajra Datatables -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('web.menu.modal.index')
@else
@include('errors.no_akes')
@endcanAccess