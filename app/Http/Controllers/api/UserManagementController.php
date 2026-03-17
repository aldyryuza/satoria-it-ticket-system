<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    public function getData(Request $request)
    {
        $query = User::with(['company', 'division'])->select('users.*');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('company_name', function ($row) {
                return $row->company->company_name ?? '-';
            })
            ->addColumn('division_name', function ($row) {
                return $row->division->division_name ?? '-';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? 'Ya' : 'Tidak';
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
            $user = $data['id'] ? User::find($data['id']) : new User();
            $user->company_id = $data['company_id'];
            $user->division_id = $data['division_id'];
            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            if ($data['password']) {
                $user->password = Hash::make($data['password']);
            }
            $user->is_active = (bool) $data['is_active'];
            $user->save();
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
            $user = User::find($id);
            $user->delete();
            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result['message'] = $th->getMessage();
        }
        return response()->json($result);
    }
}
