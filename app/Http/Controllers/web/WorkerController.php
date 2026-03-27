<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\TicketRequest;
use App\Models\TicketProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public $akses_menu = [];

    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/worker.js'),
        );
    }

    public function getTitleParent()
    {
        return "Worker";
    }

    public function getTableName()
    {
        return "";
    }

    public function myTickets()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'My Tickets',
        ];
        $view = view('web.worker.my_tickets', $data);
        $put['title_content'] = 'My Tickets';
        $put['title_top'] = 'My Tickets';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'percent_progress' => 'required|integer|min:0|max:100',
            'progress_note' => 'nullable|string|max:1000'
        ]);

        $progress = new TicketProgress();
        $progress->ticket_id = $id;
        $progress->worker_id = Auth::id();
        $progress->progress_date = now()->toDateString();
        $progress->percent_progress = $request->percent_progress;
        $progress->progress_note = $request->progress_note;
        $progress->created_by = Auth::id();
        $progress->save();

        // Log progress update
        \App\Models\TicketHistory::create([
            'ticket_id' => $id,
            'user_id' => Auth::id(),
            'action' => 'PROGRESS_UPDATE',
            'description' => 'Progress updated to ' . $request->percent_progress . '%',
            'old_value' => null,
            'new_value' => ['percent_progress' => $request->percent_progress, 'progress_note' => $request->progress_note]
        ]);

        if ($request->percent_progress == 100) {
            $ticket = TicketRequest::findOrFail($id);
            $oldStatus = $ticket->status;
            $ticket->status = 'done';
            $ticket->done_actual_date = now();
            $ticket->save();

            // Log status change to done
            \App\Models\TicketHistory::create([
                'ticket_id' => $id,
                'user_id' => Auth::id(),
                'action' => 'MARK_DONE',
                'description' => 'Ticket marked as done (progress completed)',
                'old_value' => $oldStatus,
                'new_value' => 'done'
            ]);
        }

        return redirect()->back()->with('success', 'Progress updated successfully');
    }

    public function ticketHistory($id)
    {
        $ticket = TicketRequest::findOrFail($id);
        $histories = $ticket->histories;
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Ticket History',
        ];
        $data['ticket'] = $ticket;
        $data['histories'] = $histories;
        $view = view('web.worker.ticket_history', $data);
        $put['title_content'] = 'Ticket History';
        $put['title_top'] = 'Ticket History';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function startWork(Request $request, $id)
    {
        $ticket = TicketRequest::where('id', $id)->where('assigned_to', Auth::id())->firstOrFail();
        $ticket->status = 'in_progress';
        $ticket->started_at = now();
        $ticket->save();

        return redirect()->back()->with('success', 'Work started successfully');
    }

    public function markDone(Request $request, $id)
    {
        $ticket = TicketRequest::where('id', $id)->where('assigned_to', Auth::id())->firstOrFail();
        $ticket->status = 'done';
        $ticket->done_at = now();
        $ticket->save();

        return redirect()->back()->with('success', 'Ticket marked as done');
    }
}
