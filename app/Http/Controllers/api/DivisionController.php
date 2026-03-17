<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DivisionController extends Controller
{
    public function getTableName()
    {
        return "divisions";
    }

    public function getData(Request $request)
    {
        $query = Division::with('company')->select('divisions.*')->orderBy('id', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('company_name', function ($row) {
                return $row->company->company_name ?? '-';
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
            $insert = $data['id'] == '' ? new Division() : Division::find($data['id']);
            $insert->company_id = $data['company_id'];
            $insert->division_name = $data['division_name'];
            $insert->head_user_id = $data['head_user_id'] ?? null;
            $insert->save();
            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }

    public function delete($id)
    {
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $data = Division::find($id);
            $data->delete();
            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }
}
