<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{

    protected $fillable = [
        'ticket_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'uploaded_by'
    ];

    public function ticket()
    {
        return $this->belongsTo(TicketRequest::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
