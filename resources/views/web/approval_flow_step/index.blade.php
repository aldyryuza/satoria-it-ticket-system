@canAccess('master/approval-flow-step','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">Approval Flow: {{ $flow->flow_name ?? '-' }}</p>
                <input type="hidden" id="flow_id" value="{{ $flow->id ?? '' }}" />

                @canAccess('master/approval-flow-step','create')
                <button class="btn btn-primary" onclick="ApprovalFlowStep.add()">Tambah Step <i
                        class="bx bx-plus"></i></button>
                @endcanAccess

                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table id="data-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Step Order</th>
                                    <th>Approver Role</th>
                                    <th>Approver User</th>
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
