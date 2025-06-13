<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RendezVous;
use App\Models\DossierMedical;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        $stats = [];

        if ($role === 'admin') {
            $stats['usersCount'] = User::count();
            $stats['appointmentsCount'] = RendezVous::count();
            $stats['medicalRecordsCount'] = DossierMedical::count();
        } elseif ($role === 'dentist') {
            $stats['appointmentsCount'] = RendezVous::where('dentist_id', $user->id)->count();
            $stats['medicalRecordsCount'] = DossierMedical::where('dentist_id', $user->id)->count();
        } elseif ($role === 'patient') {
            $stats['appointmentsCount'] = RendezVous::where('patient_id', $user->id)->count();
        }

        return view('admin.dashboard', compact('stats'));
    }

    public function dentistDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->role !== 'dentist') {
            return redirect()->route('login');
        }
        
        $appointments = RendezVous::with('patient')
            ->where('dentist_id', $user->id)
            ->orderBy('date_heure', 'asc')
            ->paginate(10);

        $medicalRecords = DossierMedical::with('patient')
            ->where('dentist_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dentist.dashboard', compact('appointments', 'medicalRecords'));
    }

    public function patientDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->role !== 'patient') {
            return redirect()->route('login');
        }
        
        $appointments = RendezVous::with(['dentist.dentist'])
            ->where('patient_id', $user->id)
            ->orderBy('date_heure', 'asc')
            ->paginate(10);

        return view('patient.dashboard', compact('appointments'));
    }    public function redirectToDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        switch ($user->role) {
            case 'patient':
                return redirect()->route('patient.dashboard');
            case 'dentist':
                return redirect()->route('dentist.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect()->route('login');
        }
    }
}