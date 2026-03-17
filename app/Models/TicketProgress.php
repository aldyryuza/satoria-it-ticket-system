<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketProgress extends Model
{

    protected $fillable = [
        'ticket_id',
        'worker_id',
        'progress_date',
        'percent_progress',
        'progress_note'
    ];

    public function ticket()
    {
        return $this->belongsTo(TicketRequest::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
