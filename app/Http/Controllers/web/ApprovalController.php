<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\TicketApproval;
use App\Models\TicketHistory;
use App\Models\TicketRequest;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public $akses_menu = [];

    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/approval.js'),
        );
    }

    public function getTitleParent()
    {
        return "Approvals";
    }

    public function getTableName()
    {
        return "";
    }

    public function index()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Ticket Approvals',
        ];
        $view = view('web.tickets.approval', $data);
        $put['title_content'] = 'Ticket Approvals';
        $put['title_top'] = 'Ticket Approvals';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();

        try {
            $ticket = TicketRequest::findOrFail($id);

            // Authorization check (supports direct user approver, role+company approver, and multi approvers in same step)
            if (!AppServiceProvider::canUserApproveStep($ticket, Auth::user())) {
                return back()->with('error', 'You are not authorized to approve this ticket');
            }

            // Save approval
            TicketApproval::create([
                'ticket_id'   => $ticket->id,
                'step_order'  => $ticket->current_step,
                'approver_id' => session('id'),
                'status'      => 'APPROVED',
                'note'        => $request->note,
                'approved_at' => now(),
            ]);

            // Get next step order (step can have multiple approvers)
            $nextStepOrder = AppServiceProvider::getNextStepOrder(
                $ticket->company_id,
                $ticket->division_id,
                $ticket->current_step
            );

            if ($nextStepOrder !== null) {
                $oldStep = $ticket->current_step;
                $nextApproverId = AppServiceProvider::getStepPrimaryApprover(
                    $ticket->company_id,
                    $ticket->division_id,
                    $nextStepOrder
                );

                $ticket->update([
                    'current_step'     => $nextStepOrder,
                    'current_approver' => $nextApproverId,
                ]);

                // Log approval step forwarding
                TicketHistory::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::id(),
                    'action' => 'APPROVED',
                    'description' => 'Approved and forwarded to next approver | ' . $request->note,
                    'old_value' => 'Step ' . $oldStep,
                    'new_value' => 'Step ' . $ticket->current_step
                ]);

                DB::commit();

                return redirect('tickets/approval')
                    ->with('success', 'Ticket approved and forwarded to next approver');
            }

            // Final approval
            $ticket->update([
                'status' => 'APPROVED'
            ]);

            // Log final approval
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action' => 'APPROVED',
                'description' => 'Ticket fully approved | ' . $request->note,
                'old_value' => 'WAITING APPROVAL',
                'new_value' => 'APPROVED'
            ]);

            DB::commit();

            return redirect('tickets/approval')
                ->with('success', 'Ticket fully approved');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();

        try {
            $ticket = TicketRequest::findOrFail($id);

            // Authorization check (supports direct user approver, role+company approver, and multi approvers in same step)
            if (!AppServiceProvider::canUserApproveStep($ticket, Auth::user())) {
                return back()->with('error', 'You are not authorized to reject this ticket');
            }

            // Save rejection
            TicketApproval::create([
                'ticket_id'   => $ticket->id,
                'step_order'  => $ticket->current_step,
                'approver_id' => session('id'),
                'status'      => 'REJECTED',
                'note'        => $request->note,
                'approved_at' => now(),
            ]);

            // Update ticket status
            $oldStatus = $ticket->status;

            $ticket->update([
                'status' => 'REJECTED'
            ]);

            // Log ticket rejection (include note)
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action' => 'REJECTED',
                'description' => 'Ticket rejected at step ' . $ticket->current_step .
                    ($request->note ? ' | ' . $request->note : ''),
                'old_value' => $oldStatus,
                'new_value' => 'REJECTED'
            ]);

            DB::commit();

            return back()->with('success', 'Ticket rejected');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
