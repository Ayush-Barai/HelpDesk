<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'file_path',
        'original_name',
        'file_type', 
        'file_size',
    ];
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
