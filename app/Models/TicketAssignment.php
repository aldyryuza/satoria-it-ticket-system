<?php

namespace App\Models;

class TicketAssignment extends BaseModel
{
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(TicketRequest::class, 'ticket_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
