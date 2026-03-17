<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketTypeField;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TicketTypeFieldController extends Controller
{
    public function getData(Request $request)
    {
        $query = TicketTypeField::query()->orderBy('id', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_required', function ($row) {
                return $row->is_required ? 'Yes' : 'No';
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
            $item = empty($data['id']) ? new TicketTypeField() : TicketTypeField::find($data['id']);
            $item->ticket_type_id = $data['ticket_type_id'];
            $item->field_name = $data['field_name'];
            $item->field_label = $data['field_label'];
            $item->field_type = $data['field_type'];
            $item->is_required = isset($data['is_required']) && $data['is_required'] ? 1 : 0;
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
            $item = TicketTypeField::find($id);
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
