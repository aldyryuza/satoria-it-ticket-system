<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\TicketTypeField;

class TicketTypeFieldController extends Controller
{
    public function getHeaderCss()
    {
        return [
            'js-1' => asset('assets/js/controllers/ticket_type_field.js'),
        ];
    }

    public function getTitleParent()
    {
        return 'Ticket Type Field';
    }

    public function index()
    {
        $data['data_page'] = ['title' => 'Ticket Type Fields'];
        $view = view('web.ticket_type_fields.index', $data);
        $put['title_content'] = 'Ticket Type Fields';
        $put['title_top'] = 'Ticket Type Fields';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function create()
    {
        $data['data'] = [];
        $data['data_page'] = ['title' => 'Create Ticket Type Field', 'action' => 'add'];
        $view = view('web.ticket_type_fields.form.form', $data);
        $put['title_content'] = 'Ticket Type Fields';
        $put['title_top'] = 'Ticket Type Fields';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function edit($id)
    {
        $data['data'] = TicketTypeField::find($id);
        $data['data_page'] = ['title' => 'Edit Ticket Type Field', 'action' => 'edit'];
        $view = view('web.ticket_type_fields.form.form', $data);
        $put['title_content'] = 'Ticket Type Fields';
        $put['title_top'] = 'Ticket Type Fields';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail($id)
    {
        $data['data'] = TicketTypeField::find($id);
        $data['data_page'] = ['title' => 'Detail Ticket Type Field', 'action' => 'detail'];
        $view = view('web.ticket_type_fields.form.form', $data);
        $put['title_content'] = 'Ticket Type Fields';
        $put['title_top'] = 'Ticket Type Fields';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
