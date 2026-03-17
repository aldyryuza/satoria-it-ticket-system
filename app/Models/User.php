<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'company_id',
        'division_id',
        'name',
        'username',
        'email',
        'password',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function ticketsRequested()
    {
        return $this->hasMany(TicketRequest::class, 'requester_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(TicketRequest::class, 'assigned_to');
    }

    public function ticketProgress()
    {
        return $this->hasMany(TicketProgress::class, 'worker_id');
    }
}
