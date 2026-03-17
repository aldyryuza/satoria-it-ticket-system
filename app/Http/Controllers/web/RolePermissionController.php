<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Menu;
use App\Models\RolePermission;

class RolePermissionController extends Controller
{
    public function getHeaderCss()
    {
        return [
            'js-1' => asset('assets/js/controllers/roles_permission.js'),
        ];
    }

    public function getTitleParent()
    {
        return "Roles Permission";
    }

    public function index()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Roles Permission',
        ];
        $view = view('web.roles_permission.index', $data);
        $put['title_content'] = 'Roles Permission';
        $put['title_top'] = 'Roles Permission';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create()
    {
        $data['data'] = [];
        $data['roles'] = Role::all();
        $data['menus'] = Menu::all();
        $data['data_page'] = [
            'title' => 'Roles Permission Add',
            'action' => 'add',
        ];
        $view = view('web.roles_permission.form.form', $data);
        $put['title_content'] = 'Roles Permission';
        $put['title_top'] = 'Roles Permission';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $data['data'] = RolePermission::find($id);
        $data['roles'] = Role::all();
        $data['menus'] = Menu::all();
        $data['data_page'] = [
            'title' => 'Roles Permission Edit',
            'action' => 'edit',
        ];
        $view = view('web.roles_permission.form.form', $data);
        $put['title_content'] = 'Roles Permission';
        $put['title_top'] = 'Roles Permission';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($id)
    {
        $data['data'] = RolePermission::find($id);
        $data['roles'] = Role::all();
        $data['menus'] = Menu::all();
        $data['data_page'] = [
            'title' => 'Roles Permission Detail',
            'action' => 'detail',
        ];
        $view = view('web.roles_permission.form.form', $data);
        $put['title_content'] = 'Roles Permission';
        $put['title_top'] = 'Roles Permission';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
