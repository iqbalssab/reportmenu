<?php

namespace App\Controllers;

class Mplano extends BaseController
{
    public function index()
    {
        $data = [
            'title'=>'Plano Mobile IGR',
        ];

        return view('mplano/index',$data);
    }
}
