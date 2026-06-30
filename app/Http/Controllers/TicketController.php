<?php
namespace App\Http\Controllers;
use App\Models\TicketHeader;
use App\Models\TicketDetail;
use App\Models\TicketType;
use App\Models\User;
use App\Models\AssignedTicket;
use App\Models\SupportTeam;
use App\Models\SupportMember;
use App\Models\TicketImage;
use App\Models\TicketFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // /**
    //  * Apply middleware to restrict access.
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Display a list of tickets.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $supportTypes = TicketType::where('type', strtolower(Auth::user()->usertype) == 'client' ? 1 : 0)->get();

        $searchTerm = $request->input('search');

        // Get the assigned users (those who have tickets assigned directly)
        $assignedUsers = User::whereIn('id', AssignedTicket::pluck('user_id'))->get();

        // Get the team users        
        $teamUsers = User::whereIn('id', AssignedTicket::whereHas('team.supportMembers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->pluck('user_id'))->get();


        // Merge both assigned users and team users (remove duplicates)
        // $assignedUsers = $assignedUsers->merge($teamUsers)->unique('id');
        $assignedUsers = collect($assignedUsers)->merge($teamUsers)->unique('id');


        if ($user->usertype == 'Administrator') {
            $tickets = TicketHeader::with('details', 'user')
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    $query->where('description', 'like', "%{$searchTerm}%")
                        ->orWhere('priority', 'like', "%{$searchTerm}%")
                        ->orWhere('type', 'like', "%{$searchTerm}%")
                        ->orWhere('status', 'like', "%{$searchTerm}%")
                        ->orWhere('date_created', 'like', "%{$searchTerm}%")
                        ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                            $userQuery->where('lname', 'like', "%{$searchTerm}%")
                                ->orWhere('fname', 'like', "%{$searchTerm}%")
                                ->orWhere('mname', 'like', "%{$searchTerm}%");
                        });
                })
                ->orderBy('date_created', 'desc')
                ->paginate(10);

        } elseif ($user->usertype == 'Support Team') {
            // Get tickets assigned directly to the user
            $assignedTickets = AssignedTicket::where('user_id', $user->id)->pluck('ticket_id');

            // Get all assigned tickets
            $allassignedTickets = AssignedTicket::pluck('ticket_id');

            // Get tickets assigned to the team that the user is a part of
            $ticketIds = AssignedTicket::whereHas('team.supportMembers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->pluck('ticket_id');

            // Fetch tickets assigned to the user, team, or unassigned open tickets
            $tickets = TicketHeader::with(['details', 'assignedUsers', 'user']) // Ensure we fetch assigned users
                ->where(function ($query) use ($assignedTickets, $allassignedTickets, $ticketIds) {
                    $query->whereIn('id', $assignedTickets) // Tickets assigned to this user
                        ->orWhereIn('id', $ticketIds) // Team tickets                    
                        ->orWhere(function ($q) use ($allassignedTickets) {
                            $q->where('status', 'Open')
                                ->whereNotIn('id', $allassignedTickets->toArray()); // Tickets not assigned to any user
                        });
                })
                ->when($searchTerm, function ($query) use ($searchTerm, $assignedTickets, $ticketIds, $allassignedTickets) {
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('description', 'like', '%' . $searchTerm . '%')
                            ->orWhere('priority', 'like', '%' . $searchTerm . '%')
                            ->orWhere('status', 'like', '%' . $searchTerm . '%')
                            ->orWhere('date_created', 'like', '%' . $searchTerm . '%')
                            ->orWhereHas('user', function ($q) use ($searchTerm) { // Search for requested user
                                $q->where('lname', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('fname', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('mname', 'like', '%' . $searchTerm . '%');
                            });
                    });

                    $query->where(function ($q2) use ($assignedTickets, $ticketIds, $allassignedTickets) {
                        $q2->whereIn('id', $assignedTickets) // Tickets assigned to this user
                            ->orWhereIn('id', $ticketIds) // Team tickets
                            ->orWhere(function ($q3) use ($allassignedTickets) {
                                $q3->where('status', 'Open')
                                    ->whereNotIn('id', $allassignedTickets->toArray()); // Unassigned open tickets
                            });
                    });
                })
                ->orderBy('date_created', 'desc')
                ->paginate(10);

        } elseif (in_array($user->usertype, ['User', 'Client'])) {
            $tickets = TicketHeader::with(['details', 'user'])
                ->where('user_id', $user->id) // Ensure tickets belong to the logged-in user
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    return $query->where(function ($q) use ($searchTerm) {
                        $q->where('description', 'like', '%' . $searchTerm . '%')
                            ->orWhere('priority', 'like', '%' . $searchTerm . '%')
                            ->orWhere('type', 'like', '%' . $searchTerm . '%')
                            ->orWhere('status', 'like', '%' . $searchTerm . '%')
                            ->orWhere('date_created', 'like', '%' . $searchTerm . '%');
                    });
                })
                ->orderBy('date_created', 'desc')
                ->simplePaginate(10);
        }

        foreach ($tickets as $ticket) {
            // Get users assigned directly to the ticket
            $directAssignedUsers = AssignedTicket::where('ticket_id', $ticket->id)
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->toArray();

            // Get teams assigned to the ticket
            $assignedTeams = AssignedTicket::where('ticket_id', $ticket->id)
                ->whereNotNull('team_id')
                ->pluck('team_id')
                ->toArray();

            // Get users from the assigned teams
            $teamUsers = SupportMember::whereIn('team_id', $assignedTeams)
                ->pluck('user_id')
                ->toArray();

            // Merge unique user IDs (direct assignments + team members)
            $allAssignedUserIds = array_unique(array_merge($directAssignedUsers, $teamUsers));

            // Retrieve full user details, including profile pictures
            $ticket->assignedUsers = User::whereIn('id', $allAssignedUserIds)->get();
        }

        return view('tickets.index', compact('tickets', 'user', 'supportTypes', 'assignedUsers'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $user = Auth::user();
        $supportTypes = TicketType::where('type', strtolower(Auth::user()->usertype) == 'client' ? 1 : 0)->get();
        return view('tickets.create', compact('user', 'supportTypes'));
    }

    /**
     * Show the form for editing a ticket.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $ticket = TicketHeader::with(['details.user'])->findOrFail($id);
        $supportTypes = TicketType::where('type', strtolower(Auth::user()->usertype) == 'client' ? 1 : 0)->get();
        //$ticketImage = TicketImage::where('ticket_id', $id)->get();
        $ticketFile = TicketFile::where('ticket_id', $id)->get();

        // Get assigned team IDs from AssignedTicket
        $assignedTeamIds = AssignedTicket::where('ticket_id', $id)
            ->pluck('team_id')
            ->toArray();

        // Get assigned user IDs from AssignedTicket
        $assignedUserIds = AssignedTicket::where('ticket_id', $id)
            ->pluck('user_id')
            ->toArray();

        // Fetch available teams (all teams for dropdown)
        $teams = SupportTeam::all();

        // Fetch available users (all users for dropdown)
        $users = User::where('usertype', 'Support Team')->get();

        return view('tickets.edit', compact(
            'ticket',
            'supportTypes',
            'user',
            'users',
            'teams',
            'assignedTeamIds',
            'assignedUserIds',
            'ticketFile'
        ));
    }

    /**
     * Store a newly created ticket in the database.
     */
    public function store(Request $request)
    {
        // return $request->input();
        //dd($request->all());

        $request->validate([
            'subject' => 'required',
            'description' => 'required',
            // 'priority' => 'required',
            'support_type_id' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // Validate images
            'files.*' => 'mimes:pdf,doc,docx,xls,xlsx,csv,txt,zip,rar|max:20480', // Max 20MB per file
        ]);

        $seqNo = TicketHeader::whereYear('date_created', now()->year)
            ->whereMonth('date_created', now()->month)
            ->count() + 1;
        $ticketNumber = date('Ym') . '-' . str_pad($seqNo, 4, '0', STR_PAD_LEFT);

        $ticket = TicketHeader::create([
            'ticket_number' => $ticketNumber,
            'description' => $request->subject,
            'user_id' => Auth::user()->id,
            'priority' => 'Low', //$request->priority,
            'type' => $request->support_type_id,
            'status' => 'Open',
            'date_created' => now()
        ]);

        TicketDetail::create([
            'ticket_id' => $ticket->id,
            'message' => $request->description,
            'user_id' => Auth::user()->id, // Assign later            
            'date_created' => now()
        ]);

        //Insert default team support
        $getDefaultTeam = TicketType::where('id', $request->support_type_id)->first();
        if (!empty($getDefaultTeam->default_group_id)) {
            AssignedTicket::create([
                'ticket_id' => $ticket->id,
                'team_id' => $getDefaultTeam->default_group_id,
                'created_at' => now()
            ]);
        }

        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         $path = $image->store('ticket_images', 'public');

        //         TicketImage::create([
        //             'ticket_id' => $ticket->id,
        //             'user_id' => Auth::user()->id,
        //             'img_path' => $path,
        //         ]);
        //     }
        // }

        // Save Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('ticket_images', 'public');
                TicketFile::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::user()->id,
                    'file_name' => $image->getClientOriginalName(),
                    'file_path' => $imagePath,
                    'file_type' => 'image',
                ]);
            }
        }

        // Save Other Files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('ticket_files', 'public');
                TicketFile::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::user()->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => 'document',
                ]);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show($id)
    {
        $user = Auth::user();
        $supportTypes = TicketType::all();
        $ticket = TicketHeader::with('details.user')->findOrFail($id);
        //$ticketImage = TicketImage::where('ticket_id', $id)->get();
        $ticketFile = TicketFile::where('ticket_id', $id)->get();

        // Get assigned team IDs from AssignedTicket
        $assignedTeamIds = AssignedTicket::where('ticket_id', $id)
            ->pluck('team_id')
            ->toArray();

        // Get assigned user IDs from AssignedTicket
        $assignedUsers = User::whereIn('id', function ($query) use ($id) {
            $query->select('user_id')->from('assigned_ticket')->where('ticket_id', $id);
        })->get();

        // Fetch available teams (all teams for dropdown)
        $teams = SupportTeam::all();

        // Fetch available users (all users for dropdown)
        $users = User::where('usertype', 'Support Team')->get();

        return view('tickets.show', compact('ticket', 'supportTypes', 'user', 'assignedTeamIds', 'assignedUsers', 'teams', 'users', 'ticketFile'));
    }


    /**
     * Update the specified ticket.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required',
            'priority' => 'required',
            'type' => 'required',
            'status' => 'required',
            'user_id' => 'nullable|array'
        ]);

        $ticket = TicketHeader::findOrFail($id);
        $currentUser = Auth::user();

        if ($ticket->user_id != $currentUser->id || in_array($currentUser->usertype, ['Administrator', 'Support Team'])) {
            if (!empty($request->message)) {
                TicketDetail::create([
                    'ticket_id' => $ticket->id,
                    'message' => $request->message,
                    'user_id' => $currentUser->id,
                    'date_created' => now()
                ]);
            }
        } else {
            if (!empty($request->message)) {
                $firstmessage = TicketDetail::where('ticket_id', $ticket->id)
                    ->where('user_id', $ticket->user_id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($firstmessage) {

                    $updated = $firstmessage->update([
                        'message' => $request->message,
                        'updated_at' => now()
                    ]);

                    if ($updated) {
                        \Log::info("Message updated successfully!");
                    } else {
                        \Log::error("Failed to update message for Ticket ID: {$ticket->id}");
                    }
                } else {
                    \Log::warning("No first message found for Ticket ID: {$ticket->id}");
                }
            }
        }

        $ticket->update([
            'description' => $request->description,
            'priority' => $request->priority,
            'type' => $request->type,
            'status' => $request->status,
            'updated_at' => now()
        ]);

        if (!empty($request->team_id)) {
            AssignedTicket::where('ticket_id', $ticket->id)
                ->whereNotNull('team_id')
                ->delete();

            AssignedTicket::create([
                'ticket_id' => $ticket->id,
                'team_id' => $request->team_id,
                'created_at' => now(),
            ]);
        } else {
            //No selected
            AssignedTicket::where('ticket_id', $ticket->id)
                ->whereNotNull('team_id')
                ->delete();
        }

        if (!empty($request->users)) {
            AssignedTicket::where('ticket_id', $ticket->id)
                ->whereNotNull('user_id')
                ->delete();

            foreach ($request->users as $userId) {
                AssignedTicket::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $userId,
                    'created_at' => now(),
                ]);
            }
        } else {
            //No selected
            AssignedTicket::where('ticket_id', $ticket->id)
                ->whereNotNull('user_id')
                ->delete();
        }

        // if (!empty($request->message)) {
        //     if ($request->hasFile('images')) {
        //         foreach ($request->file('images') as $image) {
        //             $path = $image->store('ticket_images', 'public');

        //             TicketImage::create([
        //                 'ticket_id' => $ticket->id,
        //                 'user_id' => $currentUser->id,
        //                 'img_path' => $path,
        //             ]);
        //         }
        //     }
        // }
        // Save Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('ticket_images', 'public');
                TicketFile::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $currentUser->id,
                    'file_name' => $image->getClientOriginalName(),
                    'file_path' => $imagePath,
                    'file_type' => 'image',
                ]);
            }
        }

        // Save Other Files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('ticket_files', 'public');
                TicketFile::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $currentUser->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => 'document',
                ]);
            }
        }
        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function deleteImage(TicketImage $image)
    {
        // Delete image file from storage
        Storage::disk('public')->delete($image->file_path);

        // Remove image record from database
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
    }
}