<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function getTableName()
    {
        return "menu";
    }

    public function getData(Request $request)
    {
        $query = Menu::select([
            'id',
            'menu_name',
            'slug',
            'route',
            'parent_id',
            'icon',
            'order_number'
        ])->orderBy('id', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i');
                // atau: ->translatedFormat('d F Y H:i') untuk "31 Desember 2024 09:15"
            }) // <=== penting untuk DT_RowIndex
            ->make(true);
    }


    public function submit(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        // dd($data);
        DB::beginTransaction();
        try {
            $data_insert = $data['id'] == '' ? new Menu() : Menu::find($data['id']);
            $data_insert->menu_name = $data['menu_name'];
            $data_insert->icon = $data['icon'];
            $data_insert->route = $data['route'];
            $data_insert->parent_id = $data['parent_menu'] ?? null;
            $data_insert->slug = $data['slug'] ?? null;
            $data_insert->save();
            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
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
            $menu = Menu::find($id);
            $menu->delete();
            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }
}
