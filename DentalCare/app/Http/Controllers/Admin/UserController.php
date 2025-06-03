<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $users = User::with('praticien')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
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

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        'password' => 'nullable|string|min:8|confirmed',
        'role' => ['required', 'in:' . implode(',', UserRole::values())],
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'date_of_birth' => 'required|date',
        'specialty' => 'required_if:role,praticien|string|max:255|nullable',
        'license_number' => 'required_if:role,praticien|string|max:50|nullable',
        'bio' => 'nullable|string',
    ]);

    $updateData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'role' => $validated['role'],
        'phone' => $validated['phone'],
        'address' => $validated['address'],
        'date_of_birth' => $validated['date_of_birth'],
    ];

    // Update password if provided
    if ($request->filled('password')) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    // Update user data
    $user->update($updateData);

    // Handle praticien-specific data
    if ($validated['role'] === UserRole::PRATICIEN->value) {
        $praticienData = [
            'specialite' => $validated['specialty'],
            'license_number' => $validated['license_number'],
            'bio' => $validated['bio'] ?? null,
        ];

        // Update or create praticien record
        if ($user->praticien) {
            $user->praticien()->update($praticienData);
        } else {
            $user->praticien()->create($praticienData);
        }
    } elseif ($user->praticien) {
        // Remove praticien record if role changed from praticien
        $user->praticien()->delete();
    }

    return redirect()->route('admin.users.index')
        ->with('success', 'User updated successfully.');
}