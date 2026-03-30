<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketHistory;
use App\Models\TicketRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TicketHistoryController extends Controller
{
    public function getHistory($ticketId)
    {
        $ticket = TicketRequest::findOrFail($ticketId);

        $histories = TicketHistory::where('ticket_id', $ticketId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        $formatted = $histories->map(function ($history) {
            return [
                'id' => $history->id,
                'action' => $history->action,
                'description' => $history->description,
                'old_value' => $history->old_value,
                'new_value' => $history->new_value,
                'user' => $history->user ? $history->user->name : 'System',
                // ISO 8601 for reliable JavaScript parsing (moment / Date)
                'created_at' => $history->created_at
                    ? $history->created_at->toIso8601String()
                    : null,
                'timestamp' => Carbon::parse($history->created_at)->format('d/m/Y H:i:s')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formatted
        ]);
    }

    public function createHistory($ticketId, $action, $description, $oldValue = null, $newValue = null)
    {
        return TicketHistory::create([
            'ticket_id' => $ticketId,
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'old_value' => $oldValue,
            'new_value' => $newValue
        ]);
    }
}
