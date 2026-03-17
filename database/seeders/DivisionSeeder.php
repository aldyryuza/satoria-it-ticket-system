<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        DB::table('divisions')->insert([
            [
                'company_id' => 2,
                'division_name' => 'Sales',
                'created_at' => now()
            ],
            [
                'company_id' => 2,
                'division_name' => 'Finance',
                'created_at' => now()
            ],
            [
                'company_id' => 3,
                'division_name' => 'Sales',
                'created_at' => now()
            ],
            [
                'company_id' => 1,
                'division_name' => 'IT',
                'created_at' => now()
            ]
        ]);
    }
}
