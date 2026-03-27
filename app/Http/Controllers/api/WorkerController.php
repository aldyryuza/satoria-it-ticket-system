<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WorkerController extends Controller
{
    public function getTableName()
    {
        return "ticket_requests";
    }

    public function getMyTickets(Request $request)
    {
        $query = TicketRequest::select([
            'id',
            'ticket_number',
            'title',
            'request_type',
            'status',
            'requester_id',
            'urgency_level',
            'created_at'
        ])->with('requester')->where('assigned_to', Auth::id())->orderBy('id', 'desc');

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i');
            })
            ->editColumn('requester_id', function ($row) {
                return $row->requester->name ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                $button = '<button class="btn btn-sm btn-info" title="View" data-id="' . $row->id . '"><i class="bx bx-show"></i></button>';
                $status = strtolower($row->status);
                if ($status === 'assigned') {
                    $button .= ' <button class="btn btn-sm btn-primary" title="Start Work" data-id="' . $row->id . '"><i class="bx bx-play"></i></button>';
                } elseif ($status === 'in_progress') {
                    $button .= ' <button class="btn btn-sm btn-warning" title="Update Progress" data-id="' . $row->id . '"><i class="bx bx-edit"></i></button>';
                    $button .= ' <button class="btn btn-sm btn-success" title="Mark Done" data-id="' . $row->id . '"><i class="bx bx-check"></i></button>';
                }
                return $button;
            })
            ->make(true);
    }

    public function getTicketHistory(Request $request)
    {
        $query = TicketRequest::select([
            'id',
            'ticket_number',
            'title',
            'request_type',
            'status',
            'requester_id',
            'urgency_level',
            'created_at'
        ])->with('requester')->where('assigned_to', Auth::id())->whereIn('status', ['done', 'closed'])->orderBy('id', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i');
            })
            ->editColumn('requester_id', function ($row) {
                return $row->requester->name ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                $button = '<button class="btn btn-sm btn-info" title="View" data-id="' . $row->id . '"><i class="bx bx-show"></i></button>';
                return $button;
            })
            ->make(true);
    }

    public function startWork(Request $request, $id)
    {
        $ticket = TicketRequest::where('id', $id)->where('assigned_to', Auth::id())->firstOrFail();

        if (strtolower($ticket->status) !== 'assigned') {
            return response()->json(['success' => false, 'message' => 'Ticket must be assigned before starting work'], 422);
        }

        $oldStatus = $ticket->status;
        $ticket->in_progress_at = now();
        $ticket->status = 'in_progress';
        $ticket->save();

        // Log status change
        \App\Models\TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'START_WORK',
            'description' => 'Started working on ticket',
            'old_value' => $oldStatus,
            'new_value' => 'in_progress'
        ]);

        return response()->json(['success' => true, 'message' => 'Work started successfully']);
    }

    public function markDone(Request $request, $id)
    {
        $ticket = TicketRequest::where('id', $id)->where('assigned_to', Auth::id())->firstOrFail();

        $oldStatus = $ticket->status;
        $ticket->status = 'done';
        $ticket->done_at = now();
        $ticket->save();

        // Log status change
        \App\Models\TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'MARK_DONE',
            'description' => 'Marked ticket as done',
            'old_value' => $oldStatus,
            'new_value' => 'done'
        ]);

        return response()->json(['success' => true, 'message' => 'Ticket marked as done']);
    }
}
