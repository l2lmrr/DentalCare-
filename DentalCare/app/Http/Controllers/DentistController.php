<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dentist;
use App\Models\RendezVous;
use App\Models\DossierMedical;
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
    }    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role !== 'dentist') {
            return redirect()->route('login');
        }        // Load dentist info and related data
        $user->load(['dentist', 'workingHours']);
        
        // Get all patients who have appointments with this dentist
        $patients = User::whereHas('appointments', function($query) use ($user) {
            $query->where('dentist_id', $user->id);
        })->where('role', 'patient')->get();

        // Get medical records with prescriptions
        $prescriptions = DossierMedical::where('dentist_id', $user->id)
            ->with(['patient' => function($query) {
                $query->select('id', 'name', 'email');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Count active patients (those with recent appointments or medical records)
        $activePatientsCount = $patients->count();
        
        // Get today's appointments with patient info
        $todayAppointments = RendezVous::where('dentist_id', $user->id)
            ->whereDate('date_heure', today())
            ->with('patient')
            ->orderBy('date_heure')
            ->get();

        // Get all upcoming appointments with patient info
        $appointments = RendezVous::where('dentist_id', $user->id)
            ->where('date_heure', '>=', now())
            ->with('patient')
            ->orderBy('date_heure')
            ->get();

        // Get upcoming appointments count
        $upcomingAppointmentsCount = $appointments->where('statut', 'confirmé')->count();        return view('dentist.dashboard', compact(
            'user',
            'patients',
            'activePatientsCount',
            'todayAppointments',
            'appointments',
            'upcomingAppointmentsCount',
            'prescriptions'
        ));

        // Get completed appointments count
        $completedAppointmentsCount = RendezVous::where('dentist_id', $user->id)            ->where('statut', 'confirmé') // Using a valid status from the enum
            ->whereDate('date_heure', '<', now()) // Only count past appointments as completed
            ->count();

        return view('dentist.dashboard', [
            'dentist' => $user,
            'dentistInfo' => $user->dentist,
            'todayAppointments' => $todayAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'activePatientsCount' => $activePatientsCount,
            'patients' => $patients,
            'prescriptions' => $prescriptions,
            'completedAppointmentsCount' => $completedAppointmentsCount
        ]);
    }
}