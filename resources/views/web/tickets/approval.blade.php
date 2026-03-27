@canAccess('approval_tickets','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    Tickets waiting for your approval.
                </p>

                @canAccess('approval_tickets','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess

                @canAccess('approval_tickets','delete')
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
                                    <th class="text-upper">requester</th>
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess
