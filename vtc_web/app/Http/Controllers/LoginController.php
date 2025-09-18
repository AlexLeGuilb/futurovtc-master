<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function authenticate(Request $request){

        if (auth()->user()->typeRole == "CHF") {
            return redirect()->route('listCourse');
        } else if (auth()->user()->typeRole == "GAR") {
            return redirect()->route('listCar');
        } else if (auth()->user()->typeRole == "HTL") {
            return redirect()->route('hotliner');
        } else if (auth()->user()->typeRole == "RH") {
            return redirect()->route('indexHR');
        } else if (auth()->user()->typeRole == "CPT") {
            return redirect()->route('getTransactions');
        } else if (auth()->user()->typeRole == "ADM") {
            return redirect()->route('indexADM');
        } else {
            return view('welcome');
        }
    }

    public function accessDenied()
    {
        return view('access_denied');
    }
}
