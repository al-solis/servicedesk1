<?php

namespace App\Http\Controllers;
use App\Models\TicketHeader;
use App\Models\User;
use App\Models\TicketDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $users = User::whereBetween('created_at', [Carbon::now()->subDays(15), Carbon::now()])->orderBy('created_at', 'desc')->get();
        $allUsers = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select('users.id', 'sessions.last_activity', 'users.lname', 'users.fname', 'users.lname', 'users.mname', 'users.email', 'users.usertype', 'users.profile_picture')
            ->orderby('sessions.last_activity', 'asc')
            ->get();

        $unAssignedTickets = TicketHeader::whereNotIn('id', function ($query) {
            $query->select('ticket_id')->from('assigned_ticket');
        })->get();

        if ($user->usertype == 'Administrator' || $user->usertype == 'Support Team') {
            $tickets = TicketHeader::all();
        } else {
            $tickets = TicketHeader::where('user_id', $user->id)->get();
        }
        return view('dashboard.index', compact('tickets', 'user', 'unAssignedTickets', 'allUsers', 'users'));
    }

    public function getTicketCounts(Request $request)
    {
        $user = Auth::user();
        $range = $request->query('range');
        if ($user->usertype == 'Administrator' || $user->usertype == 'Support Team') {
            $query = TicketHeader::all();
        } else {
            $query = TicketHeader::where('user_id', $user->id)->get();
        }

        if ($range == 'yesterday') {
            $query->whereDate('date_created', Carbon::yesterday());
        } elseif ($range == 'today') {
            $query->whereDate('date_created', Carbon::today());
        } elseif ($range == 'last7days') {
            $query->whereBetween('date_created', [Carbon::now()->subDays(7), Carbon::now()]);
        } elseif ($range == 'last30days') {
            $query->whereBetween('date_created', [Carbon::now()->subDays(30), Carbon::now()]);
        } elseif ($range == 'last90days') {
            $query->whereBetween('date_created', [Carbon::now()->subDays(90), Carbon::now()]);
        }

        return response()->json([
            'open' => $query->where('status', 'Open')->count(),
            'inprogress' => $query->where('status', 'In Progress')->count(),
            'onhold' => $query->where('status', 'On-hold')->count(),
            'closed' => $query->where('status', 'Closed')->count(),
            'cancelled' => $query->where('status', 'Cancelled')->count(),
        ]);
    }

}
