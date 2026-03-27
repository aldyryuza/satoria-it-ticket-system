@canAccess('master/approval-flow','view')
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
                    <input type="hidden" id="form_action" value="{{ $data_page['action'] }}" />
                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <select id="company_id" class="form-control required" error="Company">
                            <option value="">Pilih Company</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ isset($data->company_id) && $data->company_id ==
                                $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="division_id" class="form-label">Division</label>
                        <select id="division_id" class="form-control required" error="Division">
                            <option value="">Pilih Division</option>
                            @foreach ($divisions as $division)
                            <option value="{{ $division->id }}" {{ isset($data->division_id) && $data->division_id ==
                                $division->id ? 'selected' : '' }}>{{ $division->division_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="flow_name" class="form-label">Flow Name</label>
                        <input type="text" id="flow_name" class="form-control required" error="Flow Name"
                            value="{{ $data->flow_name ?? '' }}" />
                    </div>
                    <div class="col-sm-12 col-md-2 mb-3">
                        <label for="is_active" class="form-label">Active</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" {{ isset($data->is_active) &&
                            $data->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Yes</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Approval Steps</label>
                        @if ($data_page['action'] != 'detail')
                            <button type="button" class="btn btn-sm btn-primary" onclick="ApprovalFlow.addStepRow()">Add
                                Step</button>
                        @endif
                    </div>
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered" id="steps-table">
                            <thead>
                                <tr>
                                    <th style="width: 120px;">Step Order</th>
                                    <th>Approver Role</th>
                                    <th>Approver User</th>
                                    @if ($data_page['action'] != 'detail')
                                        <th style="width: 80px;">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="steps-body">
                                @forelse ($steps as $step)
                                    <tr class="step-row">
                                        <td>
                                            <input type="number" class="form-control step-order"
                                                value="{{ $step->step_order }}" {{ $data_page['action'] == 'detail' ? 'disabled' : '' }}>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control step-approver-role"
                                                value="{{ $step->approver_role }}" {{ $data_page['action'] == 'detail' ? 'disabled' : '' }}>
                                        </td>
                                        <td>
                                            <select class="form-control step-approver-user {{ $data_page['action'] == 'detail' ? '' : 'select2' }}"
                                                {{ $data_page['action'] == 'detail' ? 'disabled' : '' }}>
                                                <option value="">Pilih User (opsional)</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ (int) $step->approver_user_id === (int) $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        @if ($data_page['action'] != 'detail')
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="ApprovalFlow.removeStepRow(this)">x</button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    @if ($data_page['action'] == 'detail')
                                        <tr>
                                            <td colspan="4" class="text-center">No steps configured</td>
                                        </tr>
                                    @endif
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="p-3 border-top text-end">
                @if ($data_page['action'] != 'detail')
                <button class="btn btn-primary" type="button" onclick="ApprovalFlow.submit()">Save</button>
                @endif
                <button class="btn bg-danger-subtle text-danger ms-6 px-4" type="button"
                    onclick="ApprovalFlow.back()">Cancel</button>
            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess
<script>
    window.approvalFlowUsers = @json($users->map(fn($user) => ['id' => $user->id, 'name' => $user->name])->values());
</script>
