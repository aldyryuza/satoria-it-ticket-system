<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ApprovalFlow;
use App\Models\Company;
use App\Models\Division;
use App\Models\Role;
use App\Models\User;

class ApprovalFlowController extends Controller
{
    public function getHeaderCss()
    {
        return [
            'js-1' => asset('assets/js/controllers/approval_flow.js'),
        ];
    }

    public function getTitleParent()
    {
        return "Approval Flow";
    }

    public function index()
    {
        $data['data_page'] = ['title' => 'Approval Flow'];
        $view = view('web.approval_flow.index', $data);
        $put['title_content'] = 'Approval Flow';
        $put['title_top'] = 'Approval Flow';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create()
    {
        $data['data'] = [];
        $data['companies'] = Company::all();
        $data['divisions'] = Division::all();
        $data['users'] = User::all();
        $data['roles'] = Role::orderBy('role_name')->get();
        $data['steps'] = [];
        $data['data_page'] = ['title' => 'Approval Flow Create', 'action' => 'add'];
        $view = view('web.approval_flow.form.form', $data);
        $put['title_content'] = 'Approval Flow';
        $put['title_top'] = 'Approval Flow';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $data['data'] = ApprovalFlow::with('steps')->find($id);
        $data['companies'] = Company::all();
        $data['divisions'] = Division::all();
        $data['users'] = User::all();
        $data['roles'] = Role::orderBy('role_name')->get();
        $data['steps'] = $data['data'] ? $data['data']->steps->sortBy('step_order')->values() : collect();
        $data['data_page'] = ['title' => 'Approval Flow Edit', 'action' => 'edit'];
        $view = view('web.approval_flow.form.form', $data);
        $put['title_content'] = 'Approval Flow';
        $put['title_top'] = 'Approval Flow';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($id)
    {
        $data['data'] = ApprovalFlow::with('steps')->find($id);
        $data['companies'] = Company::all();
        $data['divisions'] = Division::all();
        $data['users'] = User::all();
        $data['roles'] = Role::orderBy('role_name')->get();
        $data['steps'] = $data['data'] ? $data['data']->steps->sortBy('step_order')->values() : collect();
        $data['data_page'] = ['title' => 'Approval Flow Detail', 'action' => 'detail'];
        $view = view('web.approval_flow.form.form', $data);
        $put['title_content'] = 'Approval Flow';
        $put['title_top'] = 'Approval Flow';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
