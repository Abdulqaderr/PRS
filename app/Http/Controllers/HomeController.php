<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function showHome()
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {
            return view('home');
        } else {
            return redirect('/login');
        }
    }
}