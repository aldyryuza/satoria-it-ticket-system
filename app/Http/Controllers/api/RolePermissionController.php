<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RolePermissionController extends Controller
{
    public function getTableName()
    {
        return "role_permissions";
    }

    public function getData(Request $request)
    {
        $query = RolePermission::query()
            ->leftJoin('roles', 'roles.id', '=', 'role_permissions.role_id')
            ->leftJoin('menus', 'menus.id', '=', 'role_permissions.menu_id')
            ->select(
                'role_permissions.*',
                'roles.role_name',
                'menus.menu_name'
            )
            ->orderBy('role_permissions.id', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
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
            $insert = $data['id'] == '' ? new RolePermission() : RolePermission::find($data['id']);
            $insert->role_id = $data['role_id'];
            $insert->menu_id = $data['menu_id'];
            $insert->can_view = (bool) $data['can_view'];
            $insert->can_create = (bool) $data['can_create'];
            $insert->can_update = (bool) $data['can_update'];
            $insert->can_delete = (bool) $data['can_delete'];
            $insert->can_print = (bool) $data['can_print'];
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
            $data = RolePermission::find($id);
            if ($data) {
                $data->delete();
            }
            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }

    public function deleteBulk(Request $request)
    {
        $result['is_valid'] = false;
        $ids = $request->input('ids', []);
        if (!is_array($ids) || count($ids) == 0) {
            return response()->json(['is_valid' => false, 'message' => 'Pilih data untuk dihapus.']);
        }

        DB::beginTransaction();
        try {
            RolePermission::whereIn('id', $ids)->delete();
            DB::commit();
            $result['is_valid'] = true;
            $result['message'] = count($ids) . ' data berhasil dihapus.';
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }
}
