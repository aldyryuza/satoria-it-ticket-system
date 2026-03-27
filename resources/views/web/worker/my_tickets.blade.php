@canAccess('my_tickets','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    Your assigned tickets.
                </p>

                @canAccess('my_tickets','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess

                @canAccess('my_tickets','delete')
                <input type="hidden" name="delete" id="delete" value="1">
                @endcanAccess

                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table id="data-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-upper">no</th>
                                    <th class="text-upper">ticket_number</th>
                                    <th class="text-upper">title</th>
                                    <th class="text-upper">status</th>
                                    <th class="text-upper">PIC</th>
                                    <th class="text-upper">urgency_level</th>
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

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="progressModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <form id="progressForm" method="POST">
                @csrf

                <!-- HEADER -->
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="progressModalLabel">
                        Update Progress
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Percent Progress</label>
                        <input type="number" name="percent_progress" class="form-control" min="0" max="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Progress Note</label>
                        <textarea name="progress_note" class="form-control" rows="4"></textarea>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger-subtle text-danger waves-effect" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn bg-info-subtle text-info waves-effect">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess