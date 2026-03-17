<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleManagementController extends Controller
{
    public function getHeaderCss()
    {
        return ['js-1' => asset('assets/js/controllers/role_management.js')];
    }

    public function index()
    {
        $data['data'] = [];
        $data['data_page'] = ['title' => 'Roles'];
        $view = view('web.role_management.index', $data);
        $put['title_content'] = 'Roles';
        $put['title_top'] = 'Roles';
        $put['title_parent'] = 'Roles';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create()
    {
        $data['data'] = [];
        $data['data_page'] = ['title' => 'Role Add', 'action' => 'add'];
        $view = view('web.role_management.form.form', $data);
        $put['title_content'] = 'Roles';
        $put['title_top'] = 'Roles';
        $put['title_parent'] = 'Roles';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $data['data'] = Role::find($id);
        $data['data_page'] = ['title' => 'Role Edit', 'action' => 'edit'];
        $view = view('web.role_management.form.form', $data);
        $put['title_content'] = 'Roles';
        $put['title_top'] = 'Roles';
        $put['title_parent'] = 'Roles';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($id)
    {
        $data['data'] = Role::find($id);
        $data['data_page'] = ['title' => 'Role Detail', 'action' => 'detail'];
        $view = view('web.role_management.form.form', $data);
        $put['title_content'] = 'Roles';
        $put['title_top'] = 'Roles';
        $put['title_parent'] = 'Roles';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
