<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TicketAttachment;
use App\Models\TicketRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    public function upload(Request $request, $ticketId)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // Max 10MB
        ]);

        $ticket = TicketRequest::findOrFail($ticketId);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tickets/' . $ticketId, $filename, 'public');

            $attachment = TicketAttachment::create([
                'ticket_id' => $ticketId,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => $attachment
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file provided'
        ], 400);
    }

    public function list($ticketId)
    {
        $ticket = TicketRequest::findOrFail($ticketId);
        $attachments = TicketAttachment::where('ticket_id', $ticketId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attachments
        ]);
    }

    public function download($attachmentId)
    {
        $attachment = TicketAttachment::findOrFail($attachmentId);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    public function delete($attachmentId)
    {
        $attachment = TicketAttachment::findOrFail($attachmentId);

        // Check if user is owner or admin
        if ($attachment->uploaded_by != Auth::id() && !Auth::user()->hasRole(['IT Admin', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }
}
