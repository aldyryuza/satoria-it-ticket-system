<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {

        $roles = DB::table('roles')->get();
        $menus = DB::table('menus')->get();

        foreach ($roles as $role) {

            foreach ($menus as $menu) {

                $canView = false;
                $canCreate = false;
                $canUpdate = false;
                $canDelete = false;
                $canPrint = false;

                if ($role->role_name == 'super_admin') {
                    $canView = true;
                    $canCreate = true;
                    $canUpdate = true;
                    $canDelete = true;
                    $canPrint = true;
                }

                if ($role->role_name == 'it_admin') {
                    if (in_array($menu->menu_name, [
                        'Dashboard',
                        'All Tickets',
                        'Approval Tickets',
                        'Reports'
                    ])) {
                        $canView = true;
                        $canCreate = true;
                        $canUpdate = true;
                        $canPrint = true;
                    }
                }

                if ($role->role_name == 'it_worker') {
                    if (in_array($menu->menu_name, [
                        'Dashboard',
                        'My Tickets'
                    ])) {
                        $canView = true;
                        $canUpdate = true;
                    }
                }

                if ($role->role_name == 'head_division') {
                    if (in_array($menu->menu_name, [
                        'Dashboard',
                        'Approval Tickets'
                    ])) {
                        $canView = true;
                        $canUpdate = true;
                    }
                }

                if ($role->role_name == 'requester') {
                    if (in_array($menu->menu_name, [
                        'Dashboard',
                        'Create Ticket',
                        'My Tickets'
                    ])) {
                        $canView = true;
                        $canCreate = true;
                    }
                }

                DB::table('role_permissions')->insert([
                    'role_id' => $role->id,
                    'menu_id' => $menu->id,
                    'can_view' => $canView,
                    'can_create' => $canCreate,
                    'can_update' => $canUpdate,
                    'can_delete' => $canDelete,
                    'can_print' => $canPrint
                ]);
            }
        }
    }
}
