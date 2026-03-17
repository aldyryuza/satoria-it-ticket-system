<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalFlow extends Model
{

    protected $fillable = [
        'company_id',
        'division_id',
        'flow_name',
        'is_active'
    ];

    public function steps()
    {
        return $this->hasMany(ApprovalFlowStep::class, 'flow_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
