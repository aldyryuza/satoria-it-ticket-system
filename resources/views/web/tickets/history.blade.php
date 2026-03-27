@canAccess('tickets_history','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    List of your ticket requests.
                </p>

                @canAccess('tickets_history','create')
                <button class="btn btn-primary" onclick="Ticket.create()">
                    Create New Ticket <i class="bx bx-plus"></i>
                </button>
                @endcanAccess

                @canAccess('tickets_history','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess

                @canAccess('tickets_history','delete')
                <input type="hidden" name="delete" id="delete" value="1">
                @endcanAccess

                <div class="row mt-3 mb-3">
                    <div class="col-md-3">
                        <label for="filter_status">Filter Status</label>
                        <select class="form-control select2" id="filter_status" name="filter_status">
                            <option value="">Semua Status</option>
                            <option value="DRAFT">DRAFT</option>
                            <option value="WAITING APPROVAL">WAITING APPROVAL</option>
                            <option value="ASSIGNED">ASSIGNED</option>
                            <option value="in_progress">IN PROGRESS</option>
                            <option value="done">Done</option>
                            <option value="closed">CLOSED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table id="data-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-upper">no</th>
                                    <th class="text-upper">ticket_number</th>
                                    <th class="text-upper">title</th>
                                    <th class="text-upper">request_type</th>
                                    <th class="text-upper">urgency_level</th>
                                    <th class="text-upper">status</th>
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
@else
@include('errors.no_akes')
@endcanAccess

@section('scripts')
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
