<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function getTableName()
    {
        return "ticket_requests";
    }

    public function getAllTickets(Request $request)
    {
        $query = TicketRequest::select([
            'id',
            'ticket_number',
            'request_type',
            'title',
            'status',
            'requester_id',
            'assigned_to',
            'created_at'
        ])->with(['requester', 'worker'])->orderBy('updated_at', 'desc');

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i');
            })
            ->editColumn('requester_id', function ($row) {
                return $row->requester->name ?? 'N/A';
            })
            ->editColumn('assigned_to', function ($row) {
                return $row->worker->name ?? 'Not Assigned';
            })
            ->addColumn('action', function ($row) {
                $buttons = '<button class="btn btn-sm btn-info btn-view" data-id="' . $row->id . '" title="View"><i class="bx bx-show"></i></button>';
                if ($row->status === 'APPROVED' || $row->status === 'ASSIGNED') {
                    $buttons .= ' <button class="btn btn-sm btn-primary btn-assign" 
                        data-id="' . $row->id . '" 
                        data-assigned="' . ($row->assigned_to ?? '') . '" 
                        title="Assign/Reassign">
                        <i class="bx bx-user-plus"></i>
                    </button>';
                }
                return $buttons;
            })
            ->make(true);
    }

    public function assignTicket(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'plan_due_date' => 'nullable|date',
            'note' => 'nullable|string|max:1000'
        ]);

        $ticket = TicketRequest::findOrFail($id);
        $oldAssigned = $ticket->assigned_to ? $ticket->assigned_to : null;

        $ticket->assigned_to = $request->assigned_to;
        $ticket->plan_due_date = $request->plan_due_date;
        $ticket->assignment_note = $request->note;
        $ticket->assigned_by = auth()->id();
        $ticket->assigned_at = now();

        $ticket->status = 'ASSIGNED';
        $ticket->save();

        // Create history log
        \App\Models\TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'ASSIGNED',
            'description' => 'Ticket assigned to worker',
            'old_value' => $oldAssigned ? ['assigned_to' => $oldAssigned] : null,
            'new_value' => ['assigned_to' => $request->assigned_to, 'plan_due_date' => $request->plan_due_date]
        ]);

        return response()->json(['success' => true, 'message' => 'Ticket assigned successfully']);
    }

    public function closeTicket(Request $request, $id)
    {
        $ticket = TicketRequest::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->closed_at = now();
        $ticket->save();

        // History close
        \App\Models\TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'CLOSED',
            'description' => 'Ticket closed',
            'old_value' => ['status' => 'assigned'],
            'new_value' => ['status' => 'closed', 'done_actual_date' => $ticket->done_actual_date]
        ]);

        return response()->json(['success' => true, 'message' => 'Ticket closed successfully']);
    }
}
