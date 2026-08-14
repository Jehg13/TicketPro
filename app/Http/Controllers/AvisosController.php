<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AvisosController extends Controller
{
    public function create(){
        return view('user.avisos');
    }

}
