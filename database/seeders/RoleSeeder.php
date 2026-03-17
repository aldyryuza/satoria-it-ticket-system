<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'super_admin',
                'description' => 'System Administrator'
            ],
            [
                'role_name' => 'it_admin',
                'description' => 'IT Administrator'
            ],
            [
                'role_name' => 'it_worker',
                'description' => 'IT Worker'
            ],
            [
                'role_name' => 'head_division',
                'description' => 'Division Head'
            ],
            [
                'role_name' => 'requester',
                'description' => 'User Requester'
            ]
        ]);
    }
}
