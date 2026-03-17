<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTypeField extends Model
{
    protected $table = 'ticket_type_fields';

    protected $fillable = [
        'ticket_type_id',
        'field_name',
        'field_label',
        'field_type',
        'is_required',
    ];
}
