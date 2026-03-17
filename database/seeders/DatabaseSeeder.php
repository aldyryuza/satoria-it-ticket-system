<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $this->call([
            CompanySeeder::class,
            DivisionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
            ApprovalFlowSeeder::class,
            ApprovalFlowStepSeeder::class,
            MenuSeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
