<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = RendezVous::with(['patient', 'dentist'])
            ->where('patient_id', auth()->id())
            ->orderBy('date_heure')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create(User $dentist)
    {
        return view('appointments.create', compact('dentist'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dentist_id' => 'required|exists:users,id',
            'date_heure' => 'required|date|after:now',
        ]);

        RendezVous::create([
            'patient_id' => auth()->id(),
            'dentist_id' => $validated['dentist_id'],
            'date_heure' => $validated['date_heure'],
            'statut' => 'confirmé',
        ]);

        return redirect()->route('dashboard')->with('success', 'Appointment created successfully');
    }

    public function show(RendezVous $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    public function edit(RendezVous $appointment)
    {
        return view('modals.edit-appointment', compact('appointment'));
    }

    public function update(Request $request, RendezVous $appointment)
    {
        $validated = $request->validate([
            'date_heure' => 'required|date',
            'statut' => 'required|in:confirmé,annulé,reporté',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('dashboard')->with('success', 'Appointment updated successfully');
    }

    public function destroy(RendezVous $appointment)
    {
        $appointment->delete();
        return redirect()->route('dashboard')->with('success', 'Appointment deleted');
    }

    public function cancel(RendezVous $appointment)
    {
        $appointment->update(['statut' => 'annulé']);
        return redirect()->back()->with('success', 'Appointment cancelled');
    }

    public function confirm(RendezVous $appointment)
    {
        $appointment->update(['statut' => 'confirmé']);
        return redirect()->back()->with('success', 'Appointment confirmed');
    }
}