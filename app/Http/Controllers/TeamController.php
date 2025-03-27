<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTeam;
use App\Models\SupportMember;
use App\Models\User;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = SupportTeam::with('supportMembers')
            ->whereHas('supportMembers', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        // Extract team IDs from the collection
        $teamIds = $teams->pluck('id')->toArray();

        // Fetch tickets assigned to the user's teams        
        $tickets = DB::table('ticket_header')
            ->leftJoin('assigned_ticket', 'ticket_header.id', '=', 'assigned_ticket.ticket_id')
            ->join('ticket_detail', function ($join) {
                $join->on('ticket_header.id', '=', 'ticket_detail.ticket_id')
                    ->on('ticket_header.user_id', '=', 'ticket_detail.user_id');
            })
            ->join('users', 'ticket_header.user_id', '=', 'users.id')
            ->leftJoin('support_team', 'assigned_ticket.team_id', '=', 'support_team.id')
            ->where(function ($query) use ($teamIds) {
                $query->whereIn('support_team.id', $teamIds) // Tickets assigned to the team
                    ->orWhereNull('assigned_ticket.ticket_id'); // Tickets that are not assigned
            })
            ->select('ticket_header.*', 'users.lname', 'users.fname', 'ticket_detail.message')
            ->get();


        return view('teams.index', compact('teams', 'tickets'));
    }

}
