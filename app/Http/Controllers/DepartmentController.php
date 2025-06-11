<?php

namespace App\Http\Controllers;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $searchTerm = $request->input('search');
        $department = Department::where('description', 'like', '%' . $searchTerm . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view("department.index", compact("department", "user"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required'
        ]);

        Department::create([
            'description' => $request->description,
            'created_at' => now()
        ]);

        return redirect()->route('department.index')->with('success', 'Department successfully created.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $department = Department::findOrFail($id);

        return view("department.edit", compact("department", "user"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required'
        ]);

        $department = Department::find($id);
        $department->update([
            'description' => $request->description,
            'updated_at' => now()
        ]);

        return redirect()->route('department.index')->with('success', 'Department successfully updated.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $department = Department::findOrFail($id);

        return view("department.show", compact("department", "user"));
    }
}
