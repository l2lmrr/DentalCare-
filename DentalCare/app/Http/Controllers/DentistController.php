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
        }        
        $user->load(['dentist', 'workingHours']);
        
        $patients = User::whereHas('appointments', function($query) use ($user) {
            $query->where('dentist_id', $user->id);
        })->where('role', 'patient')->get();

        $prescriptions = DossierMedical::where('dentist_id', $user->id)
            ->with(['patient' => function($query) {
                $query->select('id', 'name', 'email');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $activePatientsCount = $patients->count();
        
        $todayAppointments = RendezVous::where('dentist_id', $user->id)
            ->whereDate('date_heure', today())
            ->with('patient')
            ->orderBy('date_heure')
            ->get();

        $appointments = RendezVous::where('dentist_id', $user->id)
            ->where('date_heure', '>=', now())
            ->with('patient')
            ->orderBy('date_heure')
            ->get();

        $upcomingAppointmentsCount = $appointments->where('statut', 'confirmé')->count();        return view('dentist.dashboard', compact(
            'user',
            'patients',
            'activePatientsCount',
            'todayAppointments',
            'appointments',
            'upcomingAppointmentsCount',
            'prescriptions'
        ));

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