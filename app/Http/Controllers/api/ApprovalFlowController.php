<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ApprovalFlow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $item = empty($data['id']) ? new ApprovalFlow() : ApprovalFlow::find($data['id']);
            $item->company_id = $data['company_id'];
            $item->division_id = $data['division_id'];
            $item->flow_name = $data['flow_name'];
            $item->is_active = isset($data['is_active']) && $data['is_active'] ? 1 : 0;
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
