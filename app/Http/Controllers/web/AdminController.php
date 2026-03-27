<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\TicketRequest;
use App\Models\TicketAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public $akses_menu = [];

    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/admin.js'),
        );
    }

    public function getTitleParent()
    {
        return "Admin";
    }

    public function getTableName()
    {
        return "";
    }

    public function allTickets()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'All Tickets',
        ];
        $view = view('web.admin.all_tickets', $data);
        $put['title_content'] = 'All Tickets';
        $put['title_top'] = 'All Tickets';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function waitingAssignment()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Waiting Assignment',
        ];
        $view = view('web.admin.waiting_assignment', $data);
        $put['title_content'] = 'Waiting Assignment';
        $put['title_top'] = 'Waiting Assignment';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function inProgress()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'In Progress',
        ];
        $view = view('web.admin.in_progress', $data);
        $put['title_content'] = 'In Progress';
        $put['title_top'] = 'In Progress';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function done()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Done (Need Closing)',
        ];
        $view = view('web.admin.done', $data);
        $put['title_content'] = 'Done (Need Closing)';
        $put['title_top'] = 'Done (Need Closing)';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function closed()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Closed Tickets',
        ];
        $view = view('web.admin.closed', $data);
        $put['title_content'] = 'Closed Tickets';
        $put['title_top'] = 'Closed Tickets';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function assign(Request $request, $id)
    {
        $ticket = TicketRequest::findOrFail($id);

        if ($request->isMethod('get')) {
            // Show assignment form
            $workers = User::whereHas('roles', function ($query) {
                $query->where('name', 'IT Worker');
            })->get();

            $data['ticket'] = $ticket;
            $data['workers'] = $workers;
            $data['data_page'] = [
                'title' => 'Assign Ticket',
            ];
            $view = view('web.admin.assign', $data);
            $put['title_content'] = 'Assign Ticket';
            $put['title_top'] = 'Assign Ticket';
            $put['title_parent'] = $this->getTitleParent();
            $put['view_file'] = $view;
            $put['header_data'] = $this->getHeaderCss();
            return view('web.template.main', $put);
        } else {
            // Process assignment
            $request->validate([
                'assigned_to' => 'required|exists:users,id'
            ]);

            $assignment = new TicketAssignment();
            $assignment->ticket_id = $ticket->id;
            $assignment->assigned_by = auth()->id();
            $assignment->assigned_to = $request->assigned_to;
            $assignment->assigned_at = now();
            $assignment->save();

            $ticket->assigned_to = $request->assigned_to;
            $ticket->assigned_by = auth()->id();
            $ticket->assigned_at = now();
            $ticket->status = 'assigned';
            $ticket->save();

            return redirect()->route('admin.waiting_assignment')->with('success', 'Ticket assigned successfully');
        }
    }

    public function close($id)
    {
        $ticket = TicketRequest::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->closed_at = now();
        $ticket->save();

        return redirect()->back()->with('success', 'Ticket closed');
    }

    public function workerManagement()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Worker Management',
        ];
        $workers = User::whereHas('roles', function ($q) {
            $q->where('name', 'IT Worker');
        })->get();
        $data['workers'] = $workers;
        $view = view('web.admin.worker_management', $data);
        $put['title_content'] = 'Worker Management';
        $put['title_top'] = 'Worker Management';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
