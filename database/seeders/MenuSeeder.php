<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {

        DB::table('menus')->insert([
            [
                'id' => 1,
                'menu_name' => 'Dashboard',
                'slug' => 'dashboard',
                'route' => 'dashboard',
                'parent_id' => null,
                'icon' => 'home',
                'order_number' => 1
            ],
            [
                'id' => 2,
                'menu_name' => 'Ticket Management',
                'slug' => null,
                'route' => null,
                'parent_id' => null,
                'icon' => 'receipt',
                'order_number' => 2
            ],
            [
                'id' => 3,
                'menu_name' => 'Create Ticket',
                'slug' => 'create_tickets',
                'route' => 'tickets/create',
                'parent_id' => 2,
                'icon' => 'plus',
                'order_number' => 1
            ],
            [
                'id' => 4,
                'menu_name' => 'My Tickets',
                'slug' => 'my_tickets',
                'route' => 'tickets/my',
                'parent_id' => 2,
                'icon' => 'list',
                'order_number' => 2
            ],
            [
                'id' => 5,
                'menu_name' => 'Approval Tickets',
                'slug' => 'approval_tickets',
                'route' => 'tickets/approval',
                'parent_id' => 2,
                'icon' => 'check',
                'order_number' => 3
            ],
            [
                'id' => 6,
                'menu_name' => 'All Tickets',
                'slug' => 'all_tickets',
                'route' => 'tickets',
                'parent_id' => 2,
                'icon' => 'receipt',
                'order_number' => 4
            ],
            [
                'id' => 7,
                'menu_name' => 'User Management',
                'slug' => 'user_management',
                'route' => 'users',
                'parent_id' => null,
                'icon' => 'group',
                'order_number' => 3
            ],
            [
                'id' => 8,
                'menu_name' => 'Role Management',
                'slug' => ' role_management',
                'route' => 'roles',
                'parent_id' => null,
                'icon' => 'shield',
                'order_number' => 4
            ],
            [
                'id' => 9,
                'menu_name' => 'Reports',
                'slug' => 'reports_index',
                'route' => 'reports',
                'parent_id' => null,
                'icon' => 'pie-chart',
                'order_number' => 5
            ],
            [
                'id' => 10,
                'menu_name' => 'Master',
                'slug' => null,
                'route' => null,
                'parent_id' => null,
                'icon' => 'folder-open',
                'order_number' => 6
            ],
            [
                'id' => 11,
                'menu_name' => 'Menu',
                'slug' => 'master_menu',
                'route' => '/master/menu',
                'parent_id' => 10,
                'icon' => null,
                'order_number' => 1
            ],
        ]);
    }
}
