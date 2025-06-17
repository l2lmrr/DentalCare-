<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dentist;
use App\Models\RendezVous;
use App\Models\PlageHoraire;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'active_patients' => User::where('role', 'patient')->count(),
            'active_dentists' => User::where('role', 'dentist')->whereHas('dentist')->count(),
            'total_appointments' => RendezVous::count(),
            'weekly_consultations' => RendezVous::whereBetween('date_heure', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
        ];

        // Calculate occupancy rate
        $total_slots = PlageHoraire::count() * 8; // Assuming 8 slots per working period
        $booked_slots = RendezVous::where('statut', 'confirmé')->count();
        $stats['occupancy_rate'] = $total_slots > 0 ? round(($booked_slots / $total_slots) * 100) : 0;

        // Get recent appointments
        $recent_appointments = RendezVous::with(['patient', 'dentist'])
            ->orderBy('date_heure', 'desc')
            ->take(5)
            ->get();

        // Get dentists list
        $dentists = User::where('role', 'dentist')
            ->whereHas('dentist')
            ->with('dentist')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_appointments', 'dentists'));
    }

    public function manageDentists()
    {
        $dentists = User::where('role', 'dentist')
            ->with('dentist')
            ->get();

        return view('admin.dentists.index', compact('dentists'));
    }

    public function storeDentist(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'license_number' => 'required|unique:dentists',
            'years_of_experience' => 'required|integer|min:0',
            'bio' => 'nullable'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'dentist'
        ]);

        $user->dentist()->create([
            'license_number' => $request->license_number,
            'years_of_experience' => $request->years_of_experience,
            'bio' => $request->bio
        ]);

        return redirect()->back()->with('success', 'Dentist added successfully');
    }

    public function updateDentist(Request $request, User $dentist)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $dentist->id,
            'license_number' => 'required|unique:dentists,license_number,' . $dentist->dentist->id,
            'years_of_experience' => 'required|integer|min:0',
            'bio' => 'nullable'
        ]);

        $dentist->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $dentist->dentist->update([
            'license_number' => $request->license_number,
            'years_of_experience' => $request->years_of_experience,
            'bio' => $request->bio
        ]);

        return redirect()->back()->with('success', 'Dentist updated successfully');
    }

    public function toggleDentistStatus(User $dentist)
    {
        $dentist->dentist->update([
            'deleted_at' => $dentist->dentist->deleted_at ? null : now()
        ]);

        return response()->json(['status' => 'success']);
    }

    public function manageSchedule()
    {
        $dentists = User::where('role', 'dentist')
            ->whereHas('dentist')
            ->with(['dentist', 'workingHours'])
            ->get();

        return view('admin.schedule.index', compact('dentists'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'dentist_id' => 'required|exists:users,id',
            'jour' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut'
        ]);

        PlageHoraire::create($request->all());

        return redirect()->back()->with('success', 'Working hours added successfully');
    }

    public function deleteSchedule(PlageHoraire $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Working hours deleted successfully');
    }

    public function appointments()
    {
        $appointments = RendezVous::with(['patient', 'dentist'])
            ->orderBy('date_heure', 'desc')
            ->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function cancelAppointment(RendezVous $appointment)
    {
        $appointment->update(['statut' => 'annulé']);
        return redirect()->back()->with('success', 'Appointment cancelled successfully');
    }
}
