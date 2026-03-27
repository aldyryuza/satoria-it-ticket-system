@canAccess('admin/closed','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    Closed tickets.
                </p>

                @canAccess('admin/closed','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess

                @canAccess('admin/closed','delete')
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
@else
@include('errors.no_akes')
@endcanAccess
