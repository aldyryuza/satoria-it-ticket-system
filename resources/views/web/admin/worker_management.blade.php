@canAccess('admin/worker-management','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    Manage IT workers.
                </p>

                @canAccess('admin/worker-management','create')
                <button class="btn btn-primary" onclick="Admin.addWorker()">
                    Add Worker <i class="bx bx-plus"></i>
                </button>
                @endcanAccess

                @canAccess('admin/worker-management','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess

                @canAccess('admin/worker-management','delete')
                <input type="hidden" name="delete" id="delete" value="1">
                @endcanAccess

                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table id="data-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-upper">no</th>
                                    <th class="text-upper">name</th>
                                    <th class="text-upper">email</th>
                                    <th class="text-upper">created_at</th>
                                    <th class="text-upper">action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workers as $worker)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $worker->name }}</td>
                                    <td>{{ $worker->email }}</td>
                                    <td>{{ $worker->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if(session('update') == 1)
                                        <button class="btn btn-sm btn-warning" title="Edit"
                                            onclick="Admin.editWorker({{ $worker->id }})">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        @endif
                                        @if(session('delete') == 1)
                                        <button class="btn btn-sm btn-danger" title="Delete"
                                            onclick="Admin.deleteWorker({{ $worker->id }})">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
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
