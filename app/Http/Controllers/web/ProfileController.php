<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect()->route('auth.login');
        }

        $user = User::with(['roles'])->findOrFail(Session::get('id'));

        $data['user'] = $user;
        $data['data_page'] = [
            'title' => 'My Profile',
        ];

        $view = view('web.profile.index', $data);
        $put['title_content'] = 'My Profile';
        $put['title_top'] = 'My Profile';
        $put['title_parent'] = 'Account';
        $put['view_file'] = $view;

        return view('web.template.main', $put);
    }

    public function update(Request $request)
    {
        if (!Session::has('id')) {
            return redirect()->route('auth.login');
        }

        $user = User::findOrFail(Session::get('id'));

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        Session::put('username', $user->username);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function switchRole(Request $request)
    {
        if (!Session::has('id')) {
            return redirect()->route('auth.login');
        }

        $validated = $request->validate([
            'role_id' => 'required|integer',
        ]);

        $authUser = User::with(['company', 'division', 'roles.permissions'])->findOrFail(Session::get('id'));
        $selectedRole = $authUser->roles->firstWhere('id', (int) $validated['role_id']);

        if (!$selectedRole) {
            return redirect()->route('profile.index')->with('error', 'Selected role is not assigned to your account.');
        }

        $permissions = [];
        foreach ($selectedRole->permissions as $perm) {
            $menu = \App\Models\Menu::find($perm->menu_id);
            if (!$menu) {
                continue;
            }

            $permissions[$menu->slug] = [
                'view'   => (bool) $perm->can_view,
                'create' => (bool) $perm->can_create,
                'update' => (bool) $perm->can_update,
                'delete' => (bool) $perm->can_delete,
                'print'  => (bool) $perm->can_print,
            ];
        }

        // Refresh key session pieces after role switch.
        Session::put('roles_id', $selectedRole->id);
        Session::put('role_name', $selectedRole->role_name);
        Session::put('permissions', $permissions);
        Session::put('user_roles', $authUser->roles->values()->toArray());
        Cache::forget('menu_user_' . $authUser->id);
        $request->session()->regenerate();

        // Ensure guard user is synced with updated DB data.
        Auth::login($authUser);

        return redirect()->route('dashboard.index')->with('success', 'Role switched successfully.');
    }
}
