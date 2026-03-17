<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MenuService
{

    public function getUserMenus($userId, $roleId = null)
    {
        return Cache::remember('menu_user_' . $userId, 3600, function () use ($userId, $roleId) {

            if (!$roleId) {
                $roleId = DB::table('user_roles')
                    ->where('user_id', $userId)
                    ->value('role_id');
            }

            $menus = DB::table('menus')
                ->join('role_permissions', 'menus.id', '=', 'role_permissions.menu_id')
                ->where('role_permissions.role_id', $roleId)
                ->where('role_permissions.can_view', true)
                ->select('menus.*')
                ->distinct()
                ->orderBy('order_number')
                ->get();
            // dd($menus);
            return $this->buildMenuTree($menus);
        });
    }

    private function buildMenuTree($menus)
    {

        $tree = [];

        foreach ($menus as $menu) {

            if (!$menu->parent_id) {

                $menu->children = [];

                foreach ($menus as $child) {

                    if ($child->parent_id == $menu->id) {
                        $menu->children[] = $child;
                    }
                }

                $tree[] = $menu;
            }
        }

        return $tree;
    }

    public function getBreadcrumb($url)
    {
        $menu = \App\Models\Menu::where('route', $url)->first();

        if (!$menu) {
            return [];
        }

        $breadcrumbs = [];

        while ($menu) {

            $breadcrumbs[] = $menu;

            $menu = \App\Models\Menu::find($menu->parent_id);
        }

        return array_reverse($breadcrumbs);
    }
}
