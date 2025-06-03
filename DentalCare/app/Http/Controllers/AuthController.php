<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

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
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'in:' . implode(',', UserRole::values())],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'specialty' => 'required_if:role,praticien|string|max:255|nullable',
            'license_number' => 'required_if:role,praticien|string|max:50|nullable',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
        ]);

        if ($validated['role'] === UserRole::PRATICIEN->value) {
            $user->praticien()->create([
                'specialite' => $validated['specialty'],
                'bio' => '',
                'license_number' => $validated['license_number'],
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
        return match($role) {
            UserRole::ADMIN->value => redirect()->route('admin.dashboard'),
            UserRole::PRATICIEN->value => redirect()->route('dentist.dashboard'),
            default => redirect()->route('patient.dashboard'),
        };
    }
}