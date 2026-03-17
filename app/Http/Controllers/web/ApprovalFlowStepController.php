<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ApprovalFlow;
use App\Models\ApprovalFlowStep;
use App\Models\User;

class ApprovalFlowStepController extends Controller
{
    public function getHeaderCss()
    {
        return [
            'js-1' => asset('assets/js/controllers/approval_flow_step.js'),
        ];
    }

    public function getTitleParent()
    {
        return "Approval Flow Step";
    }

    public function index($flowId)
    {
        $flow = ApprovalFlow::find($flowId);
        $data['flow'] = $flow;
        $data['data_page'] = ['title' => 'Approval Flow Steps'];
        $view = view('web.approval_flow_step.index', $data);
        $put['title_content'] = 'Approval Flow Steps';
        $put['title_top'] = 'Approval Flow Steps';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create($flowId)
    {
        $flow = ApprovalFlow::find($flowId);
        $data['flow'] = $flow;
        $data['data'] = [];
        $data['users'] = User::all();
        $data['data_page'] = ['title' => 'Create Step', 'action' => 'add'];
        $view = view('web.approval_flow_step.form.form', $data);
        $put['title_content'] = 'Approval Flow Steps';
        $put['title_top'] = 'Approval Flow Steps';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($flowId, $id)
    {
        $flow = ApprovalFlow::find($flowId);
        $data['flow'] = $flow;
        $data['data'] = ApprovalFlowStep::find($id);
        $data['users'] = User::all();
        $data['data_page'] = ['title' => 'Edit Step', 'action' => 'edit'];
        $view = view('web.approval_flow_step.form.form', $data);
        $put['title_content'] = 'Approval Flow Steps';
        $put['title_top'] = 'Approval Flow Steps';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($flowId, $id)
    {
        $flow = ApprovalFlow::find($flowId);
        $data['flow'] = $flow;
        $data['data'] = ApprovalFlowStep::find($id);
        $data['users'] = User::all();
        $data['data_page'] = ['title' => 'Detail Step', 'action' => 'detail'];
        $view = view('web.approval_flow_step.form.form', $data);
        $put['title_content'] = 'Approval Flow Steps';
        $put['title_top'] = 'Approval Flow Steps';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
