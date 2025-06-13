<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle registration
   public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',            'role' => 'required|in:patient,dentist,admin',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'date_of_birth' => ['required', 'date', function ($attribute, $value, $fail) use ($request) {
                if ($request->input('role') === 'dentist') {
                    $age = \Carbon\Carbon::parse($value)->age;
                    if ($age < 23) {
                        $fail('Dentists must be at least 23 years old.');
                    }
                }
            }],
            'license_number' => 'required_if:role,dentist|string|max:50|nullable',
            'years_of_experience' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
        ]);        // If registering as a dentist
        if ($validated['role'] === 'dentist') {
            Dentist::create([
                'user_id' => $user->id,
                'bio' => $validated['bio'] ?? '',
                'license_number' => $validated['license_number'],
                'years_of_experience' => $validated['years_of_experience'] ?? 0,
                // photo can be added later through profile update
            ]);
        }

        Auth::login($user);

        return $this->redirectBasedOnRole($user->role);
    }
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            return $this->redirectBasedOnRole(auth()->user()->role);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    // Role-based redirection
    protected function redirectBasedOnRole(string $role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'dentist':
                return redirect()->route('dentist.dashboard');
            case 'patient':
                return redirect()->route('patient.dashboard');
            default:
                return redirect('/');
        }
    }
}