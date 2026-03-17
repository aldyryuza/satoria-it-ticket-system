@canAccess('setting/approval-flow-step','view')
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-body pb-0">
                <h4 class="card-title text-uppercase">FORM {{ $data_page['title'] }}</h4>
                <p class="card-subtitle mb-3">Plese fill the form</p>
            </div>
            <div class="card-body border-top">
                <div class="row">
                    <input type="hidden" id="id" value="{{ $data['id'] ?? '' }}" />
                    <input type="hidden" id="flow_id" value="{{ $flow->id ?? '' }}" />
                    <div class="col-sm-12 col-md-3 mb-3">
                        <label for="step_order" class="form-label">Step Order</label>
                        <input type="number" id="step_order" class="form-control required" error="Step Order"
                            value="{{ $data->step_order ?? '' }}" />
                    </div>
                    <div class="col-sm-12 col-md-3 mb-3">
                        <label for="approver_role" class="form-label">Approver Role</label>
                        <input type="text" id="approver_role" class="form-control required" error="Approver Role"
                            value="{{ $data->approver_role ?? '' }}" />
                    </div>
                    <div class="col-sm-12 col-md-3 mb-3">
                        <label for="approver_user_id" class="form-label">Approver User</label>
                        <select id="approver_user_id" class="form-control">
                            <option value="">Pilih User (opsional)</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ isset($data->approver_user_id) && $data->approver_user_id
                                == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-3 border-top text-end">
                @if ($data_page['action'] != 'detail')
                <button class="btn btn-primary" type="button" onclick="ApprovalFlowStep.submit()">Save</button>
                @endif
                <button class="btn bg-danger-subtle text-danger ms-6 px-4" type="button"
                    onclick="window.location.href = '{{ url('/setting/approval-flows/' . ($flow->id ?? 0) . '/steps') }}'">Cancel</button>
            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess
