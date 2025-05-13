<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'empid' => ['required', 'integer'],
            'lname' => ['required', 'string', 'max:255'],
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Query employee from Payroll database
        //uncomment this if you want to use the payroll database
        $employee = DB::connection('mysql')->table('tblPREmployee')
            ->where('strEmployeeID', $request->empid)
            ->where('dtmTerminated', null)
            ->first();

        if (!$employee) {
            return redirect()->back()->withErrors(['empid' => 'Employee ID not found in Payroll database.']);
        } else {
            if (
                strtoupper($employee->strLastName) != strtoupper($request->lname) ||
                strtoupper($employee->strFirstName) != strtoupper($request->fname)
            ) {
                return redirect()->back()->withErrors(['lname' => 'Name does not match in Payroll database.']);
            }
        }


        // Check if employee ID already exists
        if (User::where('empid', $request->empid)->exists()) {
            return redirect()->back()->withErrors(['empid' => 'Employee ID already registered.']);
        }

        $user = User::create([
            'empid' => $request->empid,
            'lname' => $request->lname,
            'fname' => $request->fname,
            'mname' => $request->mname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        //return redirect(route('dashboard', absolute: false));
        return redirect()->intended(route('dashboard'));
    }
}
