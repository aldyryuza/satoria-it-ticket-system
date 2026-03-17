<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run()
    {
        DB::table('companies')->insert([
            [
                'id' => 1,
                'company_code' => 'BS',
                'company_name' => 'PT Bumi Satoria',
                'is_holding' => true,
                'created_at' => now()
            ],
            [
                'id' => 2,
                'company_code' => 'SAN',
                'company_name' => 'PT Satoria Aneka Industri',
                'is_holding' => false,
                'created_at' => now()
            ],
            [
                'id' => 3,
                'company_code' => 'SDL',
                'company_name' => 'PT Satoria Distribusi Lestari',
                'is_holding' => false,
                'created_at' => now()
            ],
            [
                'id' => 4,
                'company_code' => 'SAG',
                'company_name' => 'PT Satoria Agro Industri',
                'is_holding' => false,
                'created_at' => now()
            ]
        ]);
    }
}
