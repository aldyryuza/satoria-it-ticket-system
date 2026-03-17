<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $fillable = [
        'menu_name',
        'slug',
        'route',
        'parent_id',
        'icon',
        'order_number'
    ];

    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}
