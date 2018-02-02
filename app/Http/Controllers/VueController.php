<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VueController extends Controller
{

    public function login()
    {
        return view('vue.login');
    }

    public function game()
    {
        return view('vue.game');
    }



    public function admin()
    {
        return view('vue.admin');
    }
}
