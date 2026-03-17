<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([

            [
                'company_id' => 1,
                'division_id' => 4,
                'name' => 'Super Admin',
                'email' => 'admin@bumisatoria.com',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => now()
            ],

            [
                'company_id' => 1,
                'division_id' => 4,
                'name' => 'IT Admin',
                'email' => 'itadmin@bumisatoria.com',
                'username' => 'itadmin@bumisatoria.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => now()
            ],

            [
                'company_id' => 1,
                'division_id' => 4,
                'name' => 'IT Worker',
                'email' => 'itworker@bumisatoria.com',
                'username' => 'itworker@bumisatoria.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => now()
            ],

            [
                'company_id' => 2,
                'division_id' => 1,
                'name' => 'Head Sales Pharma',
                'email' => 'headsales@pharma.com',
                'username' => 'headsales@pharma.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => now()
            ],

            [
                'company_id' => 2,
                'division_id' => 1,
                'name' => 'Sales Staff',
                'email' => 'sales@pharma.com',
                'username' => 'sales@pharma.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => now()
            ]

        ]);
    }
}
