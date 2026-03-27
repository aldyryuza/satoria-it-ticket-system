<?php

namespace App\Models;

class TicketRequest extends BaseModel
{

    // protected $fillable = [
    //     'ticket_number',
    //     'company_id',
    //     'division_id',
    //     'requester_id',
    //     'title',
    //     'description',
    //     'request_type',
    //     'urgency_level',
    //     'status',
    //     'current_step',
    //     'current_approver',
    //     'assigned_to',
    //     'assigned_by',
    //     'assigned_at',
    //     'done_at',
    //     'closed_at'
    // ];


    protected $guarded = [];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approvals()
    {
        return $this->hasMany(TicketApproval::class, 'ticket_id');
    }

    public function progress()
    {
        return $this->hasMany(TicketProgress::class, 'ticket_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'ticket_id');
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id');
    }

    public function histories()
    {
        return $this->hasMany(TicketHistory::class, 'ticket_id');
    }

    public function fields()
    {
        return $this->hasMany(TicketField::class, 'ticket_id');
    }

    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class, 'ticket_id');
    }
}
