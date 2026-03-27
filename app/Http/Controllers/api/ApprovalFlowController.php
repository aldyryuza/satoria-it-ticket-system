<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ApprovalFlow;
use App\Models\ApprovalFlowStep;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ApprovalFlowController extends Controller
{
    public function getData(Request $request)
    {
        $query = ApprovalFlow::with(['company', 'division'])->orderBy('id', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('company_name', function ($row) {
                return $row->company->company_name ?? '-';
            })
            ->addColumn('division_name', function ($row) {
                return $row->division->division_name ?? '-';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? 'Yes' : 'No';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i');
            })
            ->make(true);
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'division_id' => 'required|exists:divisions,id',
            'flow_name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'steps' => 'required|array|min:1',
            'steps.*.step_order' => 'required|integer|min:1',
            'steps.*.approver_role' => 'required|string|max:255',
            'steps.*.approver_user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'is_valid' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $validated = $validator->validated();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $data = $request->all();
            $item = empty($data['id']) ? new ApprovalFlow() : ApprovalFlow::find($data['id']);
            $item->company_id = $validated['company_id'];
            $item->division_id = $validated['division_id'];
            $item->flow_name = $validated['flow_name'];
            $item->is_active = (bool) $validated['is_active'];
            $item->save();

            ApprovalFlowStep::where('flow_id', $item->id)->delete();
            foreach ($validated['steps'] as $step) {
                ApprovalFlowStep::create([
                    'flow_id' => $item->id,
                    'step_order' => $step['step_order'],
                    'approver_role' => $step['approver_role'],
                    'approver_user_id' => $step['approver_user_id'] ?? null,
                ]);
            }

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result['message'] = $th->getMessage();
        }
        return response()->json($result);
    }

    public function delete($id)
    {
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $item = ApprovalFlow::find($id);
            if ($item) {
                $item->delete();
            }
            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result['message'] = $th->getMessage();
        }
        return response()->json($result);
    }
}
