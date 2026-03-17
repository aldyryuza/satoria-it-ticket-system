<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketApproval extends Model
{

    protected $fillable = [
        'ticket_id',
        'step_order',
        'approver_id',
        'status',
        'note',
        'approved_at'
    ];

    public function ticket()
    {
        return $this->belongsTo(TicketRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
