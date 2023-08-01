<?php

namespace App\Controllers;

class Store extends BaseController
{
    protected $helpers = ['number'];
    public function index()
    {
        
        return view('store/previewkasir');
    }
}
