<?php

namespace App\Http\Controllers;
use app\models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Redirect;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            if ($usertype == 'admin') {
                return view('layouts.sd');
            } elseif ($usertype == 'user') {
                return view('user.dashboard');
            } else {
                return Redirect::back();
            }


        }
    }
}
