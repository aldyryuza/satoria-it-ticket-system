@canAccess('master/roles-permission','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">Welcome to the {{ $data_page['title'] }}!</p>

                <div class="d-flex gap-2 mb-2">
                    @canAccess('roles-permission','create')
                    <button class="btn btn-primary" onclick="RolePermission.add()">
                        Tambah Data <i class="bx bx-plus"></i>
                    </button>
                    @endcanAccess
                    @canAccess('roles-permission','delete')
                    <button class="btn btn-danger" onclick="RolePermission.bulkDelete()">
                        Hapus Terpilih <i class="bx bx-trash"></i>
                    </button>
                    @endcanAccess
                </div>
                @canAccess('roles-permission','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess

                @canAccess('roles-permission','delete')
                <input type="hidden" name="delete" id="delete" value="1">
                @endcanAccess

                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table id="data-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" id="select-all"></th>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Role</th>
                                    <th>Menu</th>
                                    <th>View</th>
                                    <th>Create</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                    <th>Print</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess
