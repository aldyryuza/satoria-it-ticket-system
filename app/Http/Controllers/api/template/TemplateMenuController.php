<?php

namespace App\Http\Controllers\api\template;

use App\Http\Controllers\Controller;
use App\Models\Master\PermissionUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class TemplateMenuController extends Controller
{
    public function generateMenuWeb()
    {
        if (!Session::has('user_group')) {
            return "<li class='menu-item'><span class='text-muted'>Tidak ada menu</span></li>";
        }

        $userGroup = Session::get('user_group')->id;

        // Ambil permission + relasi MasterMenu (yang tidak di-delete)
        $permissions = PermissionUsers::with('MasterMenu')
            ->where('user_group', $userGroup)
            ->whereHas('MasterMenu', function ($q) {
                $q->whereNull('deleted');
            })
            ->whereNull('deleted')
            ->get();

        if ($permissions->isEmpty()) {
            return "<li class='menu-item'><span class='text-muted'>Tidak ada menu</span></li>";
        }

        // Ambil daftar menu valid
        $menus = $permissions->pluck('MasterMenu')
            ->filter()
            ->sortBy('sort');

        return $this->buildMenu($menus);
    }

    private function buildMenu($menus, $parent = '')
    {
        $html = "";

        foreach ($menus as $menu) {
            if (($menu->parent ?? '') != $parent) continue;

            $hasChild = $this->hasChild($menus, $menu->menu_code);

            $path = trim($menu->path ?? '');
            $url = ($path !== '' && $path !== '-' && $path !== '/')
                ? URL::to($path)
                : 'javascript:void(0)';

            $menuToggle = $hasChild ? "menu-toggle" : "";

            $isActive = request()->is($path)
                || request()->is($path . '/index')
                || request()->is($path . '/add')
                || request()->is($path . '/edit');

            $active = $isActive ? "active open" : "";

            $icon = $menu->icon ?? '';
            $name = e($menu->name ?: '-');

            // ⬇️ Jika icon kosong, tidak tampilkan <i>
            $iconHtml = $icon !== '' ? "<i class='menu-icon icon-base {$icon}'></i>" : "";

            $html .= "<li class='menu-item {$active}'>";

            $html .= "
            <a href='{$url}' class='menu-link {$menuToggle}'>
                {$iconHtml}
                <div class='text-truncate' data-i18n='{$name}'>{$name}</div>
                " . ($hasChild ? "<div class='menu-icon-end'></div>" : "") . "
            </a>
        ";

            if ($hasChild) {
                $html .= "<ul class='menu-sub'>";
                $html .= $this->buildMenu($menus, $menu->menu_code);
                $html .= "</ul>";
            }

            $html .= "</li>";
        }

        return $html;
    }



    private function hasChild($menus, $menuCode)
    {
        foreach ($menus as $m) {
            if (($m->parent ?? '') === $menuCode) {
                return true;
            }
        }
        return false;
    }
}
