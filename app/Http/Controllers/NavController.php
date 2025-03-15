<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TicketHeader;
use App\Models\TicketDetail;
use App\Models\User;


class NavController extends Controller
{
    // public function notification(){
    //     $user = Auth::user();

    //     $tickets = TicketHeader::with(['details', 'user'])
    //         ->where('status', 'Open');
        
    //     return view('layouts.navbar', compact('tickets', 'user'));
    // }

}
