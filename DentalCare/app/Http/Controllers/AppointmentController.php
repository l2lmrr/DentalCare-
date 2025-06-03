<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'praticien') {
            $appointments = $user->dentistAppointments()
                ->with('patient')
                ->orderBy('date_heure')
                ->paginate(10);
        } else {
            $appointments = $user->patientAppointments()
                ->with('praticien')
                ->orderBy('date_heure')
                ->paginate(10);
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $dentists = User::where('role', 'praticien')->get();
        return view('appointments.create', compact('dentists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'praticien_id' => 'required|exists:users,id',
            'date_heure' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        $validated['patient_id'] = auth()->id();
        $validated['statut'] = 'confirmé';

        RendezVous::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

        public function show(RendezVous $appointment)
    {
        Gate::authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(RendezVous $appointment)
    {
        $this->authorize('update', $appointment);
        
        $dentists = User::where('role', 'praticien')->get();
        return view('appointments.edit', compact('appointment', 'dentists'));
    }

    public function update(Request $request, RendezVous $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'praticien_id' => 'required|exists:users,id',
            'date_heure' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(RendezVous $appointment)
    {
        $this->authorize('delete', $appointment);

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    public function cancel(RendezVous $appointment)
    {
        $this->authorize('cancel', $appointment);

        $appointment->update(['statut' => 'annulé']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function confirm(RendezVous $appointment)
    {
        $this->authorize('confirm', $appointment);

        $appointment->update(['statut' => 'confirmé']);

        return back()->with('success', 'Appointment confirmed successfully.');
    }
}