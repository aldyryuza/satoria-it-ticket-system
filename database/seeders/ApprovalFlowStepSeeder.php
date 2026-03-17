<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalFlowStepSeeder extends Seeder
{
    public function run()
    {

        DB::table('approval_flow_steps')->insert([

            [
                'flow_id' => 1,
                'step_order' => 1,
                'approver_user_id' => 4,
                'approver_role' => 'head_division'
            ],

            [
                'flow_id' => 1,
                'step_order' => 2,
                'approver_user_id' => 2,
                'approver_role' => 'it_admin'
            ],

            [
                'flow_id' => 2,
                'step_order' => 1,
                'approver_user_id' => 4,
                'approver_role' => 'head_division'
            ],

            [
                'flow_id' => 2,
                'step_order' => 2,
                'approver_user_id' => 2,
                'approver_role' => 'it_admin'
            ]

        ]);
    }
}
