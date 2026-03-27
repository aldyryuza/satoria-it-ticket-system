<?php

namespace App\Models;

class TicketField extends BaseModel
{
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(TicketRequest::class, 'ticket_id');
    }
}
