<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\TicketType;
use App\Models\SupportTeam;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $supportTeam = SupportTeam::all();
        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $categories = TicketType::with('default_group')
                ->when($searchTerm, function ($query, $searchTerm) {
                    $query->where('description', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('default_group', function ($query) use ($searchTerm) {
                            $query->where('name', 'like', '%' . $searchTerm . '%');
                        });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        } else {
            $categories = TicketType::with('default_group')->orderBy('id')->paginate(10);
        }

        return view("categories.index", compact("categories", "user", "supportTeam"));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $supportTeam = SupportTeam::all();
        $ticketType = TicketType::with('default_group')->findOrFail($id);
        return view("categories.edit", compact("ticketType", "user", "supportTeam"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'team_id' => 'nullable'
        ]);
        $ticketType = TicketType::create([
            'description' => $request->description,
            'default_group_id' => $request->team_id,
            'created_ate' => now()
        ]);
        return redirect()->route('categories.index')->with('success', 'Category successfully created.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required',
            'team_id' => 'nullable'
        ]);
        $ticketType = TicketType::find($id);
        $ticketType->update([
            'description' => $request->description,
            'default_group_id' => $request->team_id,
            'updated_at' => now()
        ]);

        return redirect()->route('categories.index')->with('success', 'Category successfully updated.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $supportTeam = SupportTeam::all();
        $ticketType = TicketType::with('default_group')->find($id);
        return view('categories.show', compact('user', 'ticketType', 'supportTeam'));
    }
}
