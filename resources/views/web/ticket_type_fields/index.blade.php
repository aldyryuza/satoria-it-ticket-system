@canAccess('tickets/type-fields','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">Master Ticket Type Fields</p>

                @canAccess('tickets/type-fields','create')
                <button class="btn btn-primary" onclick="TicketTypeField.add()">Tambah Data <i
                        class="bx bx-plus"></i></button>
                @endcanAccess
                @canAccess('tickets/type-fields','update')
                <input type="hidden" name="update" id="update" value="1">
                @endcanAccess
                @canAccess('tickets/type-fields','delete')
                <input type="hidden" name="delete" id="delete" value="1">
                @endcanAccess

                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table id="data-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Ticket Type</th>
                                    <th>Field Name</th>
                                    <th>Field Label</th>
                                    <th>Field Type</th>
                                    <th>Required</th>
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