<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\DossierMedical;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->admin();
        } elseif ($user->role === 'praticien') {
            return $this->dentist();
        } else {
            return $this->patient();
        }
    }

    protected function admin()
    {
        $stats = [
            'patients' => User::where('role', 'patient')->count(),
            'dentists' => User::where('role', 'praticien')->count(),
            'appointments' => RendezVous::count(),
            'upcoming' => RendezVous::where('date_heure', '>=', now())->count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    protected function dentist()
    {
        $dentistId = Auth::id();
        
        $appointments = RendezVous::with('patient')
            ->where('dentist_id', $dentistId)
            ->where('date_heure', '>=', now())
            ->orderBy('date_heure')
            ->paginate(10);

        $medicalRecords = DossierMedical::with('patient')
            ->where('dentist_id', $dentistId)
            ->latest()
            ->paginate(10);

        return view('dentist.dashboard', compact('appointments', 'medicalRecords'));
    }

    protected function patient()
    {
        $appointments = auth()->user()->patientAppointments()
            ->with('dentist')
            ->orderBy('date_heure')
            ->paginate(10);

        return view('dashboard.patient', compact('appointments'));
    }
}