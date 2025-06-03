<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === UserRole::ADMIN->value) {
            return $this->admin();
        } elseif ($user->role === UserRole::PRATICIEN->value) {
            return $this->dentist();
        } else {
            return $this->patient();
        }
    }

    public function admin()
    {
        $stats = [
            'patients' => User::where('role', UserRole::PATIENT->value)->count(),
            'dentists' => User::where('role', UserRole::PRATICIEN->value)->count(),
            'appointments' => RendezVous::count(),
            'upcoming' => RendezVous::where('date_heure', '>=', now())->count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    public function dentist()
    {
        $appointments = auth()->user()->dentistAppointments()
            ->with('patient')
            ->orderBy('date_heure')
            ->paginate(10);

        return view('dashboard.dentist', compact('appointments'));
    }

    public function patient()
    {
        $appointments = auth()->user()->patientAppointments()
            ->with('praticien')
            ->orderBy('date_heure')
            ->paginate(10);

        return view('dashboard.patient', compact('appointments'));
    }
}