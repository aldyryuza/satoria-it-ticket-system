<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Division;
use App\Models\User;

class UserManagementController extends Controller
{
    public function getHeaderCss()
    {
        return ['js-1' => asset('assets/js/controllers/user_management.js')];
    }

    public function index()
    {
        $data['data'] = [];
        $data['data_page'] = ['title' => 'Users'];
        $view = view('web.user_management.index', $data);
        $put['title_content'] = 'Users';
        $put['title_top'] = 'Users';
        $put['title_parent'] = 'Users';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create()
    {
        $data['data'] = [];
        $data['companies'] = Company::all();
        $data['divisions'] = Division::all();
        $data['data_page'] = ['title' => 'User Add', 'action' => 'add'];
        $view = view('web.user_management.form.form', $data);
        $put['title_content'] = 'Users';
        $put['title_top'] = 'Users';
        $put['title_parent'] = 'Users';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $data['data'] = User::find($id);
        $data['companies'] = Company::all();
        $data['divisions'] = Division::all();
        $data['data_page'] = ['title' => 'User Edit', 'action' => 'edit'];
        $view = view('web.user_management.form.form', $data);
        $put['title_content'] = 'Users';
        $put['title_top'] = 'Users';
        $put['title_parent'] = 'Users';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($id)
    {
        $data['data'] = User::find($id);
        $data['companies'] = Company::all();
        $data['divisions'] = Division::all();
        $data['data_page'] = ['title' => 'User Detail', 'action' => 'detail'];
        $view = view('web.user_management.form.form', $data);
        $put['title_content'] = 'Users';
        $put['title_top'] = 'Users';
        $put['title_parent'] = 'Users';
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
