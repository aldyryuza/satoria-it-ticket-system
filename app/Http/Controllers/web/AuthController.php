<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{

    public $akses_menu = [];


    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/auth.js'),
        );
    }

    public function getTitleParent()
    {
        return "Auth";
    }

    public function getTableName()
    {
        return "";
    }

    public function index()
    {
        // if session has id
        if (Session::has('id')) {
            return redirect()->back();
        }
        $put['title_content'] = 'Login';
        $put['title_top'] = 'Login';
        $put['title_parent'] = $this->getTitleParent();
        $put['header_data'] = $this->getHeaderCss();
        return view('web.auth.login', $put);
    }
    public function register()
    {
        if (Session::has('id')) {
            return redirect()->back();
        }
        $put['title_content'] = 'Register';
        $put['title_top'] = 'Register';
        $put['title_parent'] = $this->getTitleParent();
        $put['header_data'] = $this->getHeaderCss();
        return view('web.auth.register', $put);
    }

    public function save_session(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'user' => 'required|string',
        ]);

        $data = $request->all();
        $user = json_decode($data['user']);

        if (!$user) {
            return response()->json(['is_valid' => false, 'message' => 'User data tidak valid'], 400);
        }

        $selectedRoleId = $request->input('role_id');
        $selectedRole = null;
        if (isset($user->roles) && is_array($user->roles) && count($user->roles) > 0) {
            if ($selectedRoleId) {
                foreach ($user->roles as $role) {
                    if (isset($role->id) && $role->id == $selectedRoleId) {
                        $selectedRole = $role;
                        break;
                    }
                }
            }
            if (!$selectedRole) {
                $selectedRole = $user->roles[0];
            }
        }

        if (!$selectedRole) {
            return response()->json(['is_valid' => false, 'message' => 'Role tidak ditemukan untuk user ini'], 400);
        }

        // ======================
        // BUILD PERMISSION ARRAY
        // ======================

        $permissions = [];
        if (isset($selectedRole->permissions) && is_array($selectedRole->permissions)) {
            foreach ($selectedRole->permissions as $perm) {
                $menu = \App\Models\Menu::find($perm->menu_id);
                if (!$menu) {
                    continue;
                }
                $slug = $menu->slug;
                $permissions[$slug] = [
                    'view'   => $perm->can_view,
                    'create' => $perm->can_create,
                    'update' => $perm->can_update,
                    'delete' => $perm->can_delete,
                    'print'  => $perm->can_print,
                ];
            }
        }

        // ======================
        // SESSION
        // ======================

        Session::put('id', $user->id);
        Session::put('name', $user->name);
        Session::put('username', $user->username);

        // COMPANY
        if (isset($user->company)) {
            Session::put('company_code', $user->company->company_code ?? '');
            Session::put('company_name', $user->company->company_name ?? '');
            Session::put('is_holding', $user->company->is_holding ?? 0);
        }

        // DIVISION
        if (isset($user->division)) {
            Session::put('divisi_id', $user->division->id ?? null);
            Session::put('division_name', $user->division->division_name ?? '');
        }

        // ROLE
        Session::put('roles_id', $selectedRole->id ?? null);
        Session::put('role_name', $selectedRole->role_name ?? '');

        // PERMISSION
        Session::put('permissions', $permissions);

        return response()->json(['is_valid' => true]);
    }

    public function logout()
    {
        $userId = Session::get('id');
        Session::flush();
        Cache::forget('menu_user_' . $userId);
        return redirect()->route('auth.login');
    }
}
