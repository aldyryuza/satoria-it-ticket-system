<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;

class UserRoleManagementController extends Controller
{
    public function getHeaderCss()
    {
        return ['js-1' => asset('assets/js/controllers/user_role_management.js')];
    }

    public function index()
    {
        $data['data'] = [];
        $data['data_page'] = ['title' => 'User Roles'];
        $view = view('web.user_role_management.index', $data);
        $put['title_content'] = 'User Roles';
        $put['title_top'] = 'User Roles';
        $put['title_parent'] = 'User Roles';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create()
    {
        $data['data'] = [];
        $data['users'] = User::all();
        $data['roles'] = Role::all();
        $data['data_page'] = ['title' => 'User Role Add', 'action' => 'add'];
        $view = view('web.user_role_management.form.form', $data);
        $put['title_content'] = 'User Roles';
        $put['title_top'] = 'User Roles';
        $put['title_parent'] = 'User Roles';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $data['data'] = UserRole::find($id);
        $data['users'] = User::all();
        $data['roles'] = Role::all();
        $data['data_page'] = ['title' => 'User Role Edit', 'action' => 'edit'];
        $view = view('web.user_role_management.form.form', $data);
        $put['title_content'] = 'User Roles';
        $put['title_top'] = 'User Roles';
        $put['title_parent'] = 'User Roles';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($id)
    {
        $data['data'] = UserRole::find($id);
        $data['users'] = User::all();
        $data['roles'] = Role::all();
        $data['data_page'] = ['title' => 'User Role Detail', 'action' => 'detail'];
        $view = view('web.user_role_management.form.form', $data);
        $put['title_content'] = 'User Roles';
        $put['title_top'] = 'User Roles';
        $put['title_parent'] = 'User Roles';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
