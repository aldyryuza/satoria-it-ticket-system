<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class MenuController extends Controller
{

    public $akses_menu = [];


    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/menu.js'),
        );
    }

    public function getTitleParent()
    {
        return "Menu";
    }

    public function getTableName()
    {
        return "";
    }

    public function index()
    {
        $data['data'] = [];
        $data['data_page'] = [
            'title' => 'Menu',
        ];
        $view = view('web.menu.index', $data);
        $put['title_content'] = 'Menu';
        $put['title_top'] = 'Menu';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
    public function create()
    {
        $data['data'] = [];
        $data['data_menu'] = Menu::where('route', null)->get();
        $data['data_page'] = [
            'title' => 'Menu Add',
            'action' => 'add',
        ];
        $view = view('web.menu.form.form', $data);
        $put['title_content'] = 'Menu';
        $put['title_top'] = 'Menu';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
    public function edit($id)
    {

        $data['data'] = Menu::find($id);
        $data['data_menu'] = Menu::where('route', null)->get();
        $data['data_page'] = [
            'title' => 'Menu Edit',
            'action' => 'edit',
        ];
        $view = view('web.menu.form.form', $data);
        $put['title_content'] = 'Menu';
        $put['title_top'] = 'Menu';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
    public function detail($id)
    {
        $data['data'] = Menu::find($id);
        $data['data_menu'] = Menu::where('route', null)->get();
        $data['data_page'] = [
            'title' => 'Menu Detail',
            'action' => 'detail',
        ];
        $view = view('web.menu.form.form', $data);
        $put['title_content'] = 'Menu';
        $put['title_top'] = 'Menu';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
