<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    public function getTableName()
    {
        return "ticket_requests";
    }

    public function getData(Request $request)
    {
        $query = TicketRequest::select([
            'id',
            'ticket_number',
            'title',
            'request_type',
            'urgency_level',
            'status',
            'created_at'
        ])->where('requester_id', Auth::id());

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $query->orderBy('id', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($row) {
                $button = '<button class="btn btn-sm btn-info" title="View" onclick="Ticket.view(' . $row->id . ')"><i class="bx bx-show"></i></button>';

                // Show edit button only if ticket is draft
                if ($row->status == 'DRAFT') {
                    $button .= ' <button class="btn btn-sm btn-primary" title="Edit" onclick="Ticket.edit(' . $row->id . ')"><i class="bx bx-edit"></i></button>';
                    $button .= ' <button class="btn btn-sm btn-success" title="Submit for Approval" onclick="Ticket.submitForApproval(' . $row->id . ')"><i class="bx bx-send"></i></button>';
                }

                return $button;
            })
            ->make(true);
    }

    public function submitForApproval(Request $request, $id)
    {
        $ticket = TicketRequest::findOrFail($id);
        // Check if user is requester and ticket is draft
        if (Auth::id() != $ticket->requester_id || $ticket->status != 'DRAFT') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'You can only submit draft tickets for approval'], 403);
            }
            abort(403, 'You can only submit draft tickets for approval');
        }

        // Get first approver from approval flow
        $approverId = \App\Providers\AppServiceProvider::getFirstApprover($ticket->company_id, $ticket->division_id);

        if (!$approverId) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No approval flow found for this company and division'], 400);
            }
            return redirect()->back()->with('error', 'No approval flow found for this company and division');
        }

        $ticket->status = 'WAITING APPROVAL';
        $ticket->current_approver = $approverId;
        $ticket->current_step = 1;
        $ticket->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ticket submitted for approval successfully']);
        }

        return redirect()->route('tickets.history')->with('success', 'Ticket submitted for approval successfully');
    }
}
