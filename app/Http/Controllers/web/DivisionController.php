<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Division;
use App\Models\User;

class DivisionController extends Controller
{
    public function getHeaderCss()
    {
        return [
            'js-1' => asset('assets/js/controllers/division.js'),
        ];
    }

    public function getTitleParent()
    {
        return "Departemen";
    }

    public function index()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Departemen',
        ];
        $view = view('web.division.index', $data);
        $put['title_content'] = 'Departemen';
        $put['title_top'] = 'Departemen';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create()
    {
        $data['data'] = [];
        $data['companies'] = Company::all();
        $data['users'] = User::all();
        $data['data_page'] = [
            'title' => 'Departemen Add',
            'action' => 'add',
        ];
        $view = view('web.division.form.form', $data);
        $put['title_content'] = 'Departemen';
        $put['title_top'] = 'Departemen';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $data['data'] = Division::find($id);
        $data['companies'] = Company::all();
        $data['users'] = User::all();
        $data['data_page'] = [
            'title' => 'Departemen Edit',
            'action' => 'edit',
        ];
        $view = view('web.division.form.form', $data);
        $put['title_content'] = 'Departemen';
        $put['title_top'] = 'Departemen';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($id)
    {
        $data['data'] = Division::find($id);
        $data['companies'] = Company::all();
        $data['users'] = User::all();
        $data['data_page'] = [
            'title' => 'Departemen Detail',
            'action' => 'detail',
        ];
        $view = view('web.division.form.form', $data);
        $put['title_content'] = 'Departemen';
        $put['title_top'] = 'Departemen';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
