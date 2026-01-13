<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'subject',
        'description',
        'category',
        'severity',
        'status',
        'created_by'
    ];
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
     public function employee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
