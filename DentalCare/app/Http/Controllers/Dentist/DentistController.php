<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    public function index()
    {
        $dentists = User::where('role', 'praticien')
            ->with('dentist')
            ->paginate(10);

        return view('dentist.index', compact('dentists'));
    }

    public function show(User $dentist)
    {
        if ($dentist->role !== 'praticien') {
            abort(404);
        }

        return view('dentist.show', [
            'dentist' => $dentist->load('dentist', 'workingHours')
        ]);
    }
}