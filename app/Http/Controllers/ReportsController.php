<?php

namespace App\Http\Controllers;

use App\Models\TicketDetail;
use App\Models\TicketHeader;
use App\Models\AssignedTicket;
use App\Models\SupportTeam;
use App\Models\SupportMember;
use Illuminate\Container\Attributes\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Yajra\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function indexDetail(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            if ($user->usertype == 'User') {
                $tickets = TicketHeader::with(['details', 'user'])->where('user_id', $user->id);
            } else {
                $tickets = TicketHeader::with(['details', 'user']);
            }
            // Apply date range filter if provided
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $tickets->whereBetween('date_created', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            }

            return DataTables::of($tickets)
                ->filterColumn('description', function ($query, $keyword) {
                    $query->where('description', 'like', '%' . $keyword . '%');
                })
                ->filterColumn('priority', function ($query, $keyword) {
                    $query->where('priority', 'like', '%' . $keyword . '%');
                })
                ->filterColumn('status', function ($query, $keyword) {
                    $query->where('status', 'like', '%' . $keyword . '%');
                })
                ->filterColumn('date_created', function ($query, $keyword) {
                    $query->where('date_created', 'like', '%' . $keyword . '%');
                })
                ->filterColumn('updated_at', function ($query, $keyword) {
                    $query->where('updated_at', 'like', '%' . $keyword . '%');
                })
                ->addColumn('date_created', function ($tickets) {
                    return Carbon::parse($tickets->date_created)->format('Y-m-d');
                })
                ->addColumn('updated_at', function ($tickets) {
                    return Carbon::parse($tickets->updated_at)->format('Y-m-d');
                })
                ->addColumn('uname', function ($tickets) {
                    return $tickets->user->lname . ', ' . $tickets->user->fname . ' ' . substr($tickets->user->mname, 0, 1) . '.';
                })
                ->filterColumn('uname', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->whereRaw("CONCAT(lname, ', ', fname, ' ', LEFT(mname, 1), '.') LIKE ?", ["%{$keyword}%"]);
                    });
                })
                ->make(true);

        }
        return view('reports.index-detail');
    }

    public function indexSummary(Request $request)
    {
        $user = Auth::user(); // Get logged-in user

        if ($user->usertype == 'User') {
            $query = TicketHeader::where('ticket_header.user_id', $user->id)
                ->selectRaw("
                DATE_FORMAT(ticket_header.date_created, '%m/%d/%Y') AS formatted_date_created,
                SUM(CASE WHEN ticket_header.status = 'Open' THEN 1 ELSE 0 END) as open_count,
                SUM(CASE WHEN ticket_header.status = 'In Progress' THEN 1 ELSE 0 END) as in_progress_count,
                SUM(CASE WHEN ticket_header.status = 'On-hold' THEN 1 ELSE 0 END) as on_hold_count,
                SUM(CASE WHEN ticket_header.status = 'Closed' THEN 1 ELSE 0 END) as closed_count,
                SUM(CASE WHEN ticket_header.status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_count,
                COUNT(ticket_header.id) as total_count
            ")
                ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                    $query->whereBetween('ticket_header.date_created', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                })
                ->groupBy('formatted_date_created')
                ->get();
        } else {
            // $query = TicketHeader::selectRaw("
            //     CASE 
            //         WHEN users.id IS NOT NULL THEN CONCAT(users.lname, ' ', users.fname)
            //         ELSE CONCAT(team_users.lname, ' ', team_users.fname)
            //     END AS support_member,
            //     SUM(CASE WHEN ticket_header.status = 'Open' THEN 1 ELSE 0 END) as open_count,
            //     SUM(CASE WHEN ticket_header.status = 'In Progress' THEN 1 ELSE 0 END) as in_progress_count,
            //     SUM(CASE WHEN ticket_header.status = 'On-hold' THEN 1 ELSE 0 END) as on_hold_count,
            //     SUM(CASE WHEN ticket_header.status = 'Closed' THEN 1 ELSE 0 END) as closed_count,
            //     SUM(CASE WHEN ticket_header.status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_count,
            //     COUNT(ticket_header.id) as total_count
            // ")
            //     ->join('assigned_ticket', 'ticket_header.id', '=', 'assigned_ticket.ticket_id')
            //     ->leftJoin('users', 'assigned_ticket.user_id', '=', 'users.id') // Directly assigned users
            //     ->leftJoin('support_member', 'assigned_ticket.team_id', '=', 'support_member.team_id') // Assigned teams
            //     ->leftJoin('users as team_users', 'support_member.user_id', '=', 'team_users.id') // Users in teams
            //     ->where(function ($query) {
            //         $query->whereNotNull('assigned_ticket.user_id') // Direct assignment
            //             ->orWhereNotNull('assigned_ticket.team_id'); // Team-based assignment
            //     })
            //     ->when($request->start_date && $request->end_date, function ($query) use ($request) {
            //         $query->whereBetween('ticket_header.date_created', [
            //             Carbon::parse($request->start_date)->startOfDay(),
            //             Carbon::parse($request->end_date)->endOfDay()
            //         ]);
            //     })
            //     ->groupBy('support_member')
            //     ->get();

            $query = TicketHeader::selectRaw("
    CASE 
        WHEN users.id IS NOT NULL THEN CONCAT(users.lname, ' ', users.fname)
        ELSE CONCAT(team_users.lname, ' ', team_users.fname)
    END AS support_member,
    SUM(CASE WHEN ticket_header.status = 'Open' THEN 1 ELSE 0 END) as open_count,
    SUM(CASE WHEN ticket_header.status = 'In Progress' THEN 1 ELSE 0 END) as in_progress_count,
    SUM(CASE WHEN ticket_header.status = 'On-hold' THEN 1 ELSE 0 END) as on_hold_count,
    SUM(CASE WHEN ticket_header.status = 'Closed' THEN 1 ELSE 0 END) as closed_count,
    SUM(CASE WHEN ticket_header.status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_count,
    COUNT(ticket_header.id) as total_count
")
                ->join('assigned_ticket', 'ticket_header.id', '=', 'assigned_ticket.ticket_id')
                ->leftJoin('users', 'assigned_ticket.user_id', '=', 'users.id')
                ->leftJoin('support_member', 'assigned_ticket.team_id', '=', 'support_member.team_id')
                ->leftJoin('users as team_users', 'support_member.user_id', '=', 'team_users.id')
                ->where(function ($query) {
                    $query->whereNotNull('assigned_ticket.user_id')
                        ->orWhereNotNull('assigned_ticket.team_id');
                })
                ->groupByRaw("
    CASE 
        WHEN users.id IS NOT NULL THEN CONCAT(users.lname, ' ', users.fname)
        ELSE CONCAT(team_users.lname, ' ', team_users.fname)
    END
")
                ->get();
        }

        if ($request->ajax()) {
            return DataTables::of($query)->make(true);
        }

        return view('reports.index-summary');
    }


    public function indexExport(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            $tickets = TicketHeader::with(['details', 'user'])
                ->whereHas('details', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });

            if (!empty($request->start_date) && !empty($request->end_date)) {
                $tickets->whereBetween('date_created', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            }

            return DataTables::of($tickets)
                ->filterColumn('date_created', function ($query, $keyword) {
                    $query->where('date_created', 'like', '%' . $keyword . '%');
                })
                ->addColumn('empno', function ($ticket) {
                    return $ticket->user->empid ?? 'N/A';
                })
                ->addColumn('date_created', function ($ticket) {
                    return Carbon::parse($ticket->date_created)->format('Y-m-d');
                })
                ->addColumn('time_created', function ($ticket) {
                    return Carbon::parse($ticket->date_created)->format('H:i:s');
                })
                ->addColumn('message', function ($ticket) {
                    return $ticket->details->first()->message ?? 'No message';
                })
                ->make(true);
        }

        return view('reports.index-export');
    }

}
