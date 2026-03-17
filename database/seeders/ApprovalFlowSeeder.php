<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalFlowSeeder extends Seeder
{
    public function run()
    {
        DB::table('approval_flows')->insert([

            [
                'company_id' => 2,
                'division_id' => 1,
                'flow_name' => 'Sales Pharma Approval',
                'is_active' => true
            ],

            [
                'company_id' => 3,
                'division_id' => 3,
                'flow_name' => 'Sales Distribusi Approval',
                'is_active' => true
            ]

        ]);
    }
}
