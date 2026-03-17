<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalFlowStep extends Model
{

    protected $fillable = [
        'flow_id',
        'step_order',
        'approver_role',
        'approver_user_id'
    ];

    public function flow()
    {
        return $this->belongsTo(ApprovalFlow::class);
    }

    public function approverUser()
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }
}
