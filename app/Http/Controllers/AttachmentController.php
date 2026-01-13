<?php

namespace App\Http\Controllers;

use App\Models\Attachment ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class AttachmentController extends Controller
{
    //
    public function show(Attachment $attachment){
        if (auth()->user()->role === 'agent' || auth()->id() === $attachment->ticket->created_by) {
            return Storage::disk('private');
        }
        abort(403);        
    }
}
