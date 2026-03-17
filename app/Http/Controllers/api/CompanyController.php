<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    public function getTableName()
    {
        return "companies";
    }

    public function getData(Request $request)
    {
        $query = Company::select('*')->orderBy('id', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_holding', function ($row) {
                return $row->is_holding ? 'Ya' : 'Tidak';
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
        // dd($data);
        DB::beginTransaction();
        try {
            $insert = $data['id'] == '' ? new Company() : Company::find($data['id']);
            $insert->company_code = $data['company_code'];
            $insert->company_name = $data['company_name'];
            $insert->is_holding = (bool) $data['is_holding'];
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
            $data = Company::find($id);
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
