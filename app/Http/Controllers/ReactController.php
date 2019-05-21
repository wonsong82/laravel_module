<?php

namespace App\Http\Controllers;

class ReactController extends Controller
{
    protected $css = 'app.css';
    protected $js = 'app.js';
    protected $view = 'layout.blank';


    protected function index()
    {
        return $this->default();
    }

    protected function show()
    {
        return $this->default();
    }

    protected function create()
    {
        return $this->default();
    }

    protected function edit()
    {
        return $this->default();
    }

    protected function default()
    {
        $css = $this->css;
        $js = $this->js;
        $view = $this->view;
        return view($view, compact('js', 'css'));
    }
}
