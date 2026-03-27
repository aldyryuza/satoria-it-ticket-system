<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketRequest;
use App\Providers\AppServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    public function getTableName()
    {
        return "ticket_requests";
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = TicketRequest::select([
            'id',
            'ticket_number',
            'title',
            'requester_id',
            'urgency_level',
            'created_at',
            'status',
            'company_id',
            'division_id',
            'current_step',
            'current_approver'
        ])->with('requester')->where('status', 'WAITING APPROVAL')->orderBy('id', 'desc');

        $records = $query->get()->filter(function ($ticket) use ($user) {
            return AppServiceProvider::canUserApproveStep($ticket, $user);
        })->values();

        return DataTables::of($records)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i');
            })
            ->editColumn('requester_id', function ($row) {
                return $row->requester->name ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                $button = '<button class="btn btn-sm btn-info" title="Show" onclick="Approval.view(' . $row->id . ')"><i class="bx bx-show"></i></button>';
                $button .= ' <button class="btn btn-sm btn-success" title="Approve" onclick="Approval.approve(' . $row->id . ')"><i class="bx bx-check"></i></button>';
                $button .= ' <button class="btn btn-sm btn-danger" title="Reject" onclick="Approval.reject(' . $row->id . ')"><i class="bx bx-x"></i></button>';
                return $button;
            })
            ->make(true);
    }
}
