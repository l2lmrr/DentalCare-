<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dentist;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DentistController extends Controller
{
    public function index()
    {
        $dentists = User::with('dentist')
            ->where('role', 'dentist')
            ->paginate(10);

        return view('dentist.index', compact('dentists'));
    }    public function show(User $dentist)
    {
        if (!$dentist->isDentist()) {
            abort(404, 'Dentist not found');
        }

        $dentist->load('dentist');
        return view('dentists.show', compact('dentist'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role !== 'dentist') {
            return redirect()->route('login');
        }

        // Load dentist info and related data
        $user->load(['dentist', 'workingHours']);
        
        // Get today's appointments with patient info
        $todayAppointments = RendezVous::where('dentist_id', $user->id)
            ->whereDate('date_heure', today())
            ->with('patient')
            ->orderBy('date_heure')
            ->get();

        // Get upcoming appointments
        $upcomingAppointments = RendezVous::where('dentist_id', $user->id)
            ->whereDate('date_heure', '>', today())
            ->with('patient')
            ->orderBy('date_heure')
            ->take(5)
            ->get();

        return view('dentist.dashboard', [
            'dentist' => $user,
            'dentistInfo' => $user->dentist,
            'todayAppointments' => $todayAppointments,
            'upcomingAppointments' => $upcomingAppointments
        ]);
    }
}