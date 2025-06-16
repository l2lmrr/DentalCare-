<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dentist;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get users with role 'dentist' and their dentist profiles
        $dentists = User::whereHas('dentist')
            ->where('role', 'dentist')  // or 'praticien' based on your database
            ->with('dentist')
            ->orderBy('name')
            ->take(6)
            ->get();

        return view('home', compact('dentists'));
    }
}