@canAccess('dashboard','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dashboard</h5>
                <p class="card-text">
                    Welcome to the dashboard!
                </p>

                @canAccess('dashboard','create')
                <button class="btn btn-primary">
                    <i class="bx bx-plus"></i>
                    Tambah Data
                </button>
                @endcanAccess

                @canAccess('dashboard','update')
                <button class="btn btn-warning">
                    <i class="bx bx-edit"></i>
                    Edit
                </button>
                @endcanAccess

                @canAccess('dashboard','delete')
                <button class="btn btn-danger">
                    <i class="bx bx-trash"></i>
                    Hapus
                </button>
                @endcanAccess

                @canAccess('dashboard','print')
                <button class="btn btn-success">
                    <i class="bx bx-printer"></i>
                    Print
                </button>
                @endcanAccess

            </div>
        </div>
    </div>
</div>

@else
@include('errors.no_akes')
@endcanAccess