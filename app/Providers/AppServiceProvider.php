<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Blade;
use App\Services\MenuService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        // ==============================
        // SHARE MENU KE LEFT SIDEBAR
        // ==============================

        View::composer('web.template.leftmenu', function ($view) {

            $menus = [];

            if (Session::has('id')) {

                $userId = Session::get('id');
                $roleId = Session::get('roles_id');

                $menus = app(MenuService::class)->getUserMenus($userId, $roleId);
            }

            $view->with('menus', $menus);
        });


        // ==============================
        // BLADE DIRECTIVE PERMISSION
        // ==============================

        Blade::if('canAccess', function ($menu, $action = 'view') {

            if (session('role_name') == 'super_admin') {
                return true;
            }

            $permissions = session('permissions');

            return $permissions[$menu][$action] ?? false;
        });

        // ==============================
        // BREADCUMB
        // ==============================

        View::composer('*', function ($view) {

            $breadcrumbs = [];

            if (Session::has('id')) {

                $url =  request()->path();

                $breadcrumbs = app(MenuService::class)->getBreadcrumb($url);
            }
            $view->with('breadcrumbs', $breadcrumbs);
        });
    }
}
