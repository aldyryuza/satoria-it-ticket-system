<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\TicketRequest;
use App\Models\TicketField;
use App\Models\TicketTypeField;
use App\Models\TicketHistory;
use App\Models\Company;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public $akses_menu = [];

    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/ticket.js'),
        );
    }

    public function getTitleParent()
    {
        return "Tickets";
    }

    public function getTableName()
    {
        return "";
    }

    public function create()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Create Ticket',
        ];
        $data['companies'] = Company::all();
        $data['divisions'] = Division::all();
        $data['ticketTypes'] = TicketTypeField::select('ticket_type_id')->distinct()->get();

        // Lock company & division based on session
        $sessionCompany = Company::where('company_code', session('company_code'))->first();
        $data['session_company_id'] = $sessionCompany ? $sessionCompany->id : null;
        $data['session_division_id'] = session('divisi_id');
        $data['is_holding'] = session('is_holding', 0); // holding/super_admin can freely choose

        $view = view('web.tickets.create', $data);
        $put['title_content'] = 'Create Ticket';
        $put['title_top'] = 'Create Ticket';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'division_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'request_type' => 'required',
            'urgency_level' => 'required',
        ]);

        $ticket = new TicketRequest();
        $ticket->ticket_number = $this->generateTicketNumber($request->company_id);
        $ticket->company_id = $request->company_id;
        $ticket->division_id = $request->division_id;
        $ticket->requester_id = session('id');
        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->request_type = $request->request_type;
        $ticket->urgency_level = $request->urgency_level;
        $ticket->status = 'DRAFT';
        $ticket->current_step = 1;
        $ticket->created_by = Auth::id();
        $ticket->save();

        // Log ticket creation
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'CREATED',
            'description' => 'Ticket created',
            'old_value' => null,
            'new_value' => 'DRAFT'
        ]);

        // Save dynamic fields
        $typeFields = TicketTypeField::where('ticket_type_id', $request->request_type)->get();
        foreach ($typeFields as $field) {
            if ($request->has($field->field_name)) {
                TicketField::create([
                    'ticket_id' => $ticket->id,
                    'field_name' => $field->field_name,
                    'field_value' => $request->input($field->field_name),
                ]);
            }
        }

        // Check if request is AJAX (for file uploads)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully',
                'data' => $ticket
            ]);
        }

        return redirect()->route('tickets.history')->with('success', 'Ticket created successfully');
    }

    public function history()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'My Ticket Requests',
        ];
        $view = view('web.tickets.history', $data);
        $put['title_content'] = 'My Ticket Requests';
        $put['title_top'] = 'My Ticket Requests';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function show($id)
    {
        $ticket = TicketRequest::findOrFail($id);
        $canApproveTicket = \App\Providers\AppServiceProvider::canUserApproveStep($ticket, Auth::user());
        // Check if user is requester, admin, or assigned worker
        $isAuthorized = session('id') == $ticket->requester_id ||
            session('id') == $ticket->assigned_to ||
            session('id') == $ticket->current_approver ||
            $canApproveTicket ||
            // Auth::user()->hasRole(['IT Admin', 'Admin'])
            in_array(session('role_name'), ['super_admin', 'it_admin', 'it_worker']);

        if (!$isAuthorized) {
            abort(403, 'Unauthorized access');
        }

        $data['ticket'] = $ticket;
        $data['data_page'] = [
            'title' => 'Ticket Details - ' . $ticket->ticket_number,
        ];
        $data['can_approve_ticket'] = $canApproveTicket;
        $data['fields'] = TicketField::where('ticket_id', $id)->get();
        $view = view('web.tickets.show', $data);
        $put['title_content'] = $ticket->ticket_number;
        $put['title_top'] = $ticket->title;
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $ticket = TicketRequest::findOrFail($id);

        // Check if user is requester and ticket is pending
        if (session('id') != $ticket->requester_id || $ticket->status != 'DRAFT') {
            abort(403, 'You can only edit draft tickets');
        }

        $data['ticket'] = $ticket;
        $data['companies'] = Company::all();
        $data['divisions'] = Division::all();
        $data['ticketTypes'] = TicketTypeField::select('ticket_type_id')->distinct()->get();
        $data['fields'] = TicketField::where('ticket_id', $id)->get();
        $data['data_page'] = [
            'title' => 'Edit Ticket',
        ];

        // Lock company & division based on session (same logic as create)
        $sessionCompany = Company::where('company_code', session('company_code'))->first();
        $data['session_company_id'] = $sessionCompany ? $sessionCompany->id : null;
        $data['session_division_id'] = session('divisi_id');
        $data['is_holding'] = session('is_holding', 0);

        $view = view('web.tickets.edit', $data);
        $put['title_content'] = 'Edit Ticket';
        $put['title_top'] = 'Edit - ' . $ticket->ticket_number;
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function update(Request $request, $id)
    {
        $ticket = TicketRequest::findOrFail($id);

        // Check if user is requester and ticket is pending
        if (session('id') != $ticket->requester_id || $ticket->status != 'DRAFT') {
            abort(403, 'You can only edit draft tickets');
        }

        $request->validate([
            'company_id' => 'required',
            'division_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'request_type' => 'required',
            'urgency_level' => 'required',
        ]);

        // If company changed, regenerate ticket number to avoid duplicate key constraint
        if ($ticket->company_id != $request->company_id) {
            $ticket->ticket_number = $this->generateTicketNumber($request->company_id);
        }

        $ticket->company_id = $request->company_id;
        $ticket->division_id = $request->division_id;
        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->request_type = $request->request_type;
        $ticket->urgency_level = $request->urgency_level;
        $ticket->save();

        // Log ticket update
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'UPDATED',
            'description' => 'Ticket information updated',
            'old_value' => null,
            'new_value' => 'Updated'
        ]);

        // Update dynamic fields
        TicketField::where('ticket_id', $id)->delete();
        $typeFields = TicketTypeField::where('ticket_type_id', $request->request_type)->get();
        foreach ($typeFields as $field) {
            if ($request->has($field->field_name)) {
                TicketField::create([
                    'ticket_id' => $id,
                    'field_name' => $field->field_name,
                    'field_value' => $request->input($field->field_name),
                ]);
            }
        }

        // Check if request is AJAX (for file uploads)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket updated successfully',
                'data' => $ticket
            ]);
        }

        return redirect()->route('tickets.show', $id)->with('success', 'Ticket updated successfully');
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
        if (!\App\Providers\AppServiceProvider::hasStep($ticket->company_id, $ticket->division_id, 1)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No approval flow found for this company and division'], 400);
            }
            return redirect()->back()->with('error', 'No approval flow found for this company and division');
        }

        $oldStatus = $ticket->status;
        $approverId = \App\Providers\AppServiceProvider::getFirstApprover($ticket->company_id, $ticket->division_id);
        $ticket->status = 'WAITING APPROVAL';
        $ticket->current_approver = $approverId;
        $ticket->current_step = 1;
        $ticket->save();

        // Log submission for approval
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'SUBMITTED',
            'description' => 'Submitted for approval',
            'old_value' => $oldStatus,
            'new_value' => 'WAITING APPROVAL'
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ticket submitted for approval successfully']);
        }

        return redirect()->route('tickets.history')->with('success', 'Ticket submitted for approval successfully');
    }

    private function generateTicketNumber($companyId)
    {
        $company = Company::find($companyId);
        $month = date('m');
        $year = date('Y');
        $lastTicket = TicketRequest::where('company_id', $companyId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $running = $lastTicket ? intval(substr($lastTicket->ticket_number, -4)) + 1 : 1;
        return 'TCK-' . $company->company_code . '-' . $month . '-' . $year . '-' . str_pad($running, 4, '0', STR_PAD_LEFT);
    }

    public function destroy($id)
    {
        $ticket = TicketRequest::findOrFail($id);
        $ticket->delete();
        return redirect()->route('tickets.history')->with('success', 'Ticket deleted successfully');
    }
}
