<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SupportTeam;
use App\Models\SupportMember;
use App\Models\User;

class SupportController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $users = User::where('usertype', 'Support Team')
            ->where('status', 'Active')
            ->get();

        return view('support.create', compact('user', 'users'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $searchTerm = $request->input('search');
        $supportTeam = SupportTeam::with([
            'supportMembers.user' => function ($query) {
                $query->whereNotNull('id');
            }
        ])
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('support.index', compact('user', 'supportTeam'));
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            'user_id' => 'nullable|array',
        ]);

        $supportTeam = SupportTeam::create([
            'name' => $data['name'],
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ]);

        if ($request->users) {
            foreach ($request->users as $userId) {
                SupportMember::create([
                    'team_id' => $supportTeam->id,
                    'user_id' => $userId,
                    'created_by' => Auth::user()->id,
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->route('support.index')->with('success', 'Support Team successfully created.');
    }

    public function edit($id)
    {
        $supportTeam = SupportTeam::findOrFail($id);
        $users = User::where('usertype', 'Support Team')
            ->where('status', 'Active')
            ->orderByRaw("CONCAT_WS(' ', lname, fname, mname) ASC")
            ->get();
        $teamMembers = SupportMember::where('team_id', $id)->pluck('user_id')->toArray();

        return view('support.edit', compact('supportTeam', 'users', 'teamMembers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'user_id' => 'nullable|array',
        ]);

        $supportTeam = SupportTeam::findOrFail($id);
        $supportTeam->update([
            'name' => $request->name,
        ]);

        // Sync Team Members
        if (!empty($request->users)) {
            SupportMember::where('team_id', $id)->delete(); // Remove old members

            foreach ($request->users as $userId) {
                SupportMember::create([
                    'team_id' => $supportTeam->id,
                    'user_id' => $userId,
                    'created_by' => Auth::user()->id,
                    'updated_at' => now(),
                ]);
            }
        } else {
            // If no users are selected, clear all team members
            SupportMember::where('team_id', $id)->delete();
        }

        return redirect()->route('support.index')->with('success', 'Team updated successfully.');

    }

}
