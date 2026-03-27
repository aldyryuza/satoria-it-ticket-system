@canAccess('all_tickets','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    List of all tickets.
                </p>

                @canAccess('all_tickets','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess

                @canAccess('all_tickets','delete')
                <input type="hidden" name="delete" id="delete" value="1">
                @endcanAccess

                <div class="row mt-3 mb-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label mb-1 fw-semibold">Filter Status</label>
                        <select id="filter-status" class="form-select select2 form-select-sm" style="min-width: 180px;">
                            <option value="">-- All Status --</option>
                            <option value="DRAFT">Draft</option>
                            <option value="WAITING APPROVAL">Waiting Approval</option>
                            <option value="APPROVED">Approved</option>
                            <option value="REJECTED">Rejected</option>
                            <option value="ASSIGNED">Assigned</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button id="btn-reset-filter" class="btn btn-sm btn-secondary">
                            <i class="bx bx-reset me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 table-responsive">
                        <table id="data-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-upper">no</th>
                                    <th class="text-upper">ticket_number</th>
                                    <th class="text-upper">request_type</th>
                                    <th class="text-upper">title</th>
                                    <th class="text-upper">status</th>
                                    <th class="text-upper">requester</th>
                                    <th class="text-upper">assigned_to</th>
                                    <th class="text-upper">created_at</th>
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

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="assignModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <form id="assignForm" method="POST">
                @csrf

                <!-- HEADER -->
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="assignModalLabel">
                        Assign Ticket
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select name="assigned_to" class="form-select" required>
                            <option value="">Select Worker</option>
                            @foreach (\App\Models\User::whereHas('roles', function ($q) {
        $q->where('role_name', 'it_worker');
    })->get() as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Plan Due Date</label>
                        <input type="date" name="plan_due_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" class="form-control" rows="4"></textarea>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger-subtle text-danger waves-effect"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn bg-primary-subtle text-primary waves-effect">
                        Assign
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess


@section('scripts')
    <script>
        $('.select2').select2({
            width: '100%'
        });
    </script>
@endsection
