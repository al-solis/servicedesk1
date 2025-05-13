<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function create()
    {
        $users = Auth::user();
        $departments = Department::all();
        return view('users.create', compact('users', 'departments'));
    }
    public function index(Request $request)
    {
        $user = Auth::user();

        $searchTerm = $request->input('search');

        if ($searchTerm) {
            $users = User::with('department')
                ->when($searchTerm, function ($query, $searchTerm) {
                    $query->where('empid', 'like', "%{$searchTerm}%")
                        ->orWhere('lname', 'like', "%{$searchTerm}%")
                        ->orWhere('fname', 'like', "%{$searchTerm}%")
                        ->orWhere('mname', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%")
                        ->orWhere('usertype', 'like', "%{$searchTerm}%")
                        ->orWhere('designation', 'like', "%{$searchTerm}%")
                        ->orWhere('telno', 'like', "%{$searchTerm}%")
                        ->orWhere('status', 'like', "%{$searchTerm}%")
                        ->orWhereHas('department', function ($query) use ($searchTerm) {
                            $query->where('description', 'like', "%{$searchTerm}%");
                        });
                })
                ->paginate(10);
        } else {
            $users = User::with('department')->paginate(10);
        }

        return view('users.index', compact('users', 'user'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $users = User::findOrFail($id);
        $departments = Department::all();
        return view('users.edit', compact('users', 'user', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'empid' => 'required',
            'lname' => 'required',
            'fname' => 'required',
            'mname' => 'nullable',
            'email' => 'required',
            'usertype' => 'required',
            'status' => 'required',
            'telno' => 'nullable',
            'dept_id' => 'nullable',
        ]);

        $users = User::findOrFail($id);
        $users->update([
            'empid' => $request->empid,
            'lname' => $request->lname,
            'fname' => $request->fname,
            'mname' => $request->mname,
            'email' => $request->email,
            'usertype' => $request->usertype,
            'designation' => $request->designation,
            'telno' => $request->telno,
            'status' => $request->status,
            'dept_id' => $request->dept_id,
            'updated_at' => now(),
        ]);

        if (Auth::user()->usertype == 'Administrator') {
            return redirect()->route('users.index')->with('success', 'User successfully updated.');
        } else {
            return redirect()->route('dashboard')->with('success', 'User successfully updated.');
        }
    }

    public function uploadPicture(Request $request, $id)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:800'
        ]);

        $users = User::findOrFail($id);

        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($users->profile_picture) {
                Storage::disk('public')->delete($users->profile_picture);
            }

            // Get the file extension
            $extension = $request->file('profile_picture')->getClientOriginalExtension();

            // Generate the new file name using the user ID
            $fileName = $users->empid . '.' . $extension;

            // Save the new profile picture with the user ID as the filename
            $path = $request->file('profile_picture')->storeAs('profile_pictures', $fileName, 'public');

            // Save the relative path to the user model
            $users->profile_picture = 'profile_pictures/' . $fileName;
            $users->save();
        }


        return back()->with('success', 'Profile picture updated successfully!');
    }

    public function deletePicture($userId)
    {
        // Get the user by ID
        $users = User::findOrFail($userId);

        // Check if a profile picture exists
        if ($users->profile_picture) {
            // Delete the profile picture from storage
            Storage::disk('public')->delete($users->profile_picture);

            // Remove the file path from the user model
            $users->profile_picture = null;
            $users->save();
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Profile picture deleted successfully');
    }

    public function show($id)
    {
        $user = Auth::user();
        $users = User::findOrFail($id);
        return view('users.show', compact('users', 'user'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($user->id == $currentUser->id && $request->status == 'Inactive') {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.'
            ], 400);
        }

        // If the user was deactivated, log them out
        if ($user->status === 'Inactive') {
            Session::getHandler()->destroy($user->session_id); // Destroy user session 
            DB::table('sessions')->where('user_id', $user->id)->delete();
            Session::flash('logout_message', 'Your account has been deactivated, and you have been logged out.');
        }

        // Toggle status
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->status
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'empid' => 'required|unique:users',
            'lname' => 'required',
            'fname' => 'required',
            'mname' => 'nullable',
            'email' => 'required|email|unique:users',
            'usertype' => 'required',
            'telno' => 'nullable',
            'dept_id' => 'nullable',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Employee ID and Email Unique Check (Optional since it's already validated)
        if (User::where('empid', $request->empid)->exists()) {
            return redirect()->back()->withErrors(['empid' => 'Employee ID already registered.'])->withInput();
        }

        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()->withErrors(['email' => 'Email already registered.'])->withInput();
        }

        User::create([
            'empid' => $request->empid,
            'lname' => Str::title($request->lname),
            'fname' => Str::title($request->fname),
            'mname' => Str::title($request->mname),
            'email' => $request->email,
            'usertype' => $request->usertype,
            'designation' => $request->designation,
            'telno' => $request->telno,
            'status' => 'Active',
            'dept_id' => $request->dept_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User successfully created.');
    }


}
