<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ApprovalFlowStep;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ApprovalFlowStepController extends Controller
{
    public function getData(Request $request, $flowId)
    {
        $query = ApprovalFlowStep::where('flow_id', $flowId)->orderBy('step_order', 'asc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('approver_user_name', function ($row) {
                return optional($row->approverUser)->name ?? '-';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i');
            })
            ->make(true);
    }

    public function submit(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $item = empty($data['id']) ? new ApprovalFlowStep() : ApprovalFlowStep::find($data['id']);
            $item->flow_id = $data['flow_id'];
            $item->step_order = $data['step_order'];
            $item->approver_role = $data['approver_role'];
            $item->approver_user_id = $data['approver_user_id'] ?: null;
            $item->save();
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
            $item = ApprovalFlowStep::find($id);
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
