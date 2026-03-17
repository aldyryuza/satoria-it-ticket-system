<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends Controller
{
    public function getHeaderCss()
    {
        return [
            'js-1' => asset('assets/js/controllers/company.js'),
        ];
    }

    public function getTitleParent()
    {
        return "Subsidiary";
    }

    public function index()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Subsidiary',
        ];
        $view = view('web.company.index', $data);
        $put['title_content'] = 'Subsidiary';
        $put['title_top'] = 'Subsidiary';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Subsidiary Add',
            'action' => 'add',
        ];
        $view = view('web.company.form.form', $data);
        $put['title_content'] = 'Subsidiary';
        $put['title_top'] = 'Subsidiary';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $data['data'] = Company::find($id);
        $data['data_page'] = [
            'title' => 'Subsidiary Edit',
            'action' => 'edit',
        ];
        $view = view('web.company.form.form', $data);
        $put['title_content'] = 'Subsidiary';
        $put['title_top'] = 'Subsidiary';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($id)
    {
        $data['data'] = Company::find($id);
        $data['data_page'] = [
            'title' => 'Subsidiary Detail',
            'action' => 'detail',
        ];
        $view = view('web.company.form.form', $data);
        $put['title_content'] = 'Subsidiary';
        $put['title_top'] = 'Subsidiary';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
