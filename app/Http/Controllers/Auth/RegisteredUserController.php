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
use Illuminate\Support\Str;
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
            'empid' => ['required', 'string', 'max:20'],
            'lname' => ['required', 'string', 'max:50'],
            'fname' => ['required', 'string', 'max:50'],
            'mname' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:50', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Query employee from Payroll database
        //uncomment this if you want to use the payroll database
        $employee = DB::connection('sqlsrv2')->table('employees')
            ->where('employee_code', $request->empid)
            ->where('termination_date', null)
            ->first();

        if (!$employee) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['empid' => 'Employee ID not found in Employee master.']);
        } else {
            if (
                strtoupper($employee->last_name) != strtoupper($request->lname) ||
                strtoupper($employee->first_name) != strtoupper($request->fname)
            ) {
                return back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->withErrors(['lname' => 'Name does not match in Employee master.']);
            }
        }


        // Check if employee ID already exists
        if (User::where('empid', $request->empid)->exists()) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['empid' => 'Employee ID already registered.']);
        }

        $user = User::create([
            'empid' => $request->empid,
            'lname' => Str::title($request->lname),
            'fname' => Str::title($request->fname),
            'mname' => Str::title($request->mname),
            'company' => $request->company ?? null,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'Inactive',
        ]);

        event(new Registered($user));

        //Auth::login($user);

        //return redirect(route('dashboard', absolute: false));
        //return redirect()->intended(route('dashboard'));
        return redirect()->route('login')->with(
            'status',
            'Registration successful. Your account is pending activation. You can log in once it has been activated.'
        );
    }
}
