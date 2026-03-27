<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Blade;
use App\Models\ApprovalFlow;
use App\Models\TicketRequest;
use App\Models\User;
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

    public static function getFirstApprover($companyId, $divisionId)
    {
        return self::getStepPrimaryApprover($companyId, $divisionId, 1);
    }

    public static function hasStep($companyId, $divisionId, $stepOrder)
    {
        $flow = self::getFlow($companyId, $divisionId);
        if (!$flow) {
            return false;
        }

        return $flow->steps()->where('step_order', $stepOrder)->exists();
    }

    public static function getStepPrimaryApprover($companyId, $divisionId, $stepOrder)
    {
        $flow = self::getFlow($companyId, $divisionId);
        if (!$flow) {
            return null;
        }

        $step = $flow->steps()
            ->where('step_order', $stepOrder)
            ->orderBy('id')
            ->first();

        return $step ? $step->approver_user_id : null;
    }

    public static function getNextStepOrder($companyId, $divisionId, $currentStep)
    {
        $flow = self::getFlow($companyId, $divisionId);
        if (!$flow) {
            return null;
        }

        $next = $flow->steps()
            ->where('step_order', '>', $currentStep)
            ->orderBy('step_order')
            ->first();

        return $next ? (int) $next->step_order : null;
    }

    public static function canUserApproveStep(TicketRequest $ticket, ?User $user)
    {
        if (!$user || $ticket->status !== 'WAITING APPROVAL') {
            return false;
        }

        $flow = self::getFlow($ticket->company_id, $ticket->division_id);
        if (!$flow) {
            return false;
        }

        $steps = $flow->steps()->where('step_order', $ticket->current_step)->get();
        if ($steps->isEmpty()) {
            return false;
        }

        $userRoleNames = $user->roles()
            ->pluck('role_name')
            ->map(function ($roleName) {
                return strtolower(trim($roleName));
            })
            ->toArray();
        // dd($steps, $userRoleNames);
        foreach ($steps as $step) {
            if (!empty($step->approver_user_id) && (int) $step->approver_user_id === (int) $user->id) {
                return true;
            }

            if (empty($step->approver_user_id)) {
                $stepRole = strtolower(trim((string) $step->approver_role));
                $isSameCompany = (int) $user->company_id === (int) $ticket->company_id;
                if ($isSameCompany && in_array($stepRole, $userRoleNames, true)) {
                    return true;
                }
            }
        }

        return false;
    }

    private static function getFlow($companyId, $divisionId)
    {
        return ApprovalFlow::where('company_id', $companyId)
            ->where('division_id', $divisionId)
            ->where('is_active', true)
            ->first();
    }
}
