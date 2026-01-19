<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Attachment $attachment)
    {
        $user = auth()->user();
        $ticket = $attachment->ticket;

        // 1. Authorization Check
        // Allow if user is an Agent OR if the user is the one who created the ticket
        if ($user->role === 'agent' || $user->id === $ticket->created_by) {

            // 2. Verify file exists
            if (! Storage::disk('private')->exists($attachment->file_path)) {
                abort(404, 'File not found on server.');
            }

            // 3. Return the download response
            return Storage::disk('private')->download(
                $attachment->file_path,
                $attachment->original_name,
                ['Content-Type' => $attachment->file_type]
            );
        }

        // If not authorized
        abort(403, 'You are not authorized to download this file.');
    }

    public function attachmentsIndex(Ticket $ticket)
    {
        // Shared security check
        // if (auth()->user()->role !== 'agent' && auth()->id() !== $ticket->created_by) abort(403);
        return view('attachments.show', compact('ticket'));
    }
}
