<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function verifAuth()
    {
        if(auth()->user()->typeRole == "ADM") {
            return true;
        } else {
            return false;
        }
    }

    public function index()
    {
        if($this->verifAuth()) {
            return view('vueADM');
        } else {
            return redirect()->route('accessDenied');
        }
    }
}
