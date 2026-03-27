<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketComment;
use App\Models\TicketRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function getComments($ticketId)
    {
        $ticket = TicketRequest::findOrFail($ticketId);

        $comments = TicketComment::where('ticket_id', $ticketId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $formatted = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name
                ],
                'created_at' => $comment->created_at->format('d/m/Y H:i')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formatted
        ]);
    }

    public function postComment(Request $request, $ticketId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $ticket = TicketRequest::findOrFail($ticketId);

        $comment = TicketComment::create([
            'ticket_id' => $ticketId,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment posted successfully',
            'data' => $comment
        ], 201);
    }

    public function deleteComment($commentId)
    {
        $comment = TicketComment::findOrFail($commentId);

        // Check if user is owner or admin
        if ($comment->user_id != Auth::id() && !Auth::user()->hasRole(['IT Admin', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}
