<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\PlageHoraire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentController extends Controller
{
    use AuthorizesRequests;
    const SLOT_DURATION = 30; 

    public function index()
    {
        $appointments = RendezVous::with(['dentist.dentist', 'patient'])
            ->where('patient_id', auth()->id())
            ->orderBy('date_heure')
            ->paginate(10);

        return view('patient.dashboard', compact('appointments'));
    }

    public function create(User $dentist)
    {
        if (!$dentist->isDentist()) {
            abort(404, 'Dentist not found');
        }

        $availableSlots = $this->getAvailableTimeSlots($dentist);
        $workingHours = $dentist->workingHours()->orderBy('jour')->get();

        return view('appointments.create', [
            'dentist' => $dentist,
            'availableSlots' => $availableSlots,
            'workingHours' => $workingHours
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dentist_id' => 'required|exists:users,id',
            'date_heure' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        $dentist = User::findOrFail($validated['dentist_id']);
        $selectedDateTime = Carbon::parse($validated['date_heure']);
        $dayOfWeek = strtolower($selectedDateTime->isoFormat('dddd'));
        $time = $selectedDateTime->format('H:i:s');

        $isWithinWorkingHours = $dentist->workingHours()
            ->where('jour', $dayOfWeek)
            ->where('heure_debut', '<=', $time)
            ->where('heure_fin', '>=', $time)
            ->exists();

        if (!$isWithinWorkingHours) {
            return back()->withErrors(['date_heure' => 'Selected time is outside of dentist\'s working hours'])->withInput();
        }

        if (!RendezVous::isTimeSlotAvailable($dentist->id, $validated['date_heure'])) {
            return back()->withErrors(['date_heure' => 'This time slot is already booked'])->withInput();
        }

        $appointment = RendezVous::create([
            'patient_id' => auth()->id(),
            'dentist_id' => $validated['dentist_id'],
            'date_heure' => $validated['date_heure'],
            'statut' => 'confirmé',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Appointment booked successfully!');
    }    public function edit(RendezVous $appointment)
    {
        $this->authorize('update', $appointment);
        
        if ($appointment->date_heure->isPast()) {
            return response()->json([
                'error' => 'Cannot edit past appointments'
            ], 403);
        }

        $workingHours = PlageHoraire::where('dentist_id', $appointment->dentist_id)
            ->orderBy('jour')
            ->get();
            
        $availableSlots = $this->getAvailableTimeSlots($appointment->dentist);

        return view('modals.edit-appointment', compact('appointment', 'workingHours', 'availableSlots'));
    }

    public function update(Request $request, RendezVous $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->date_heure->isPast()) {
            return back()->with('error', 'Cannot edit past appointments');
        }

            $validated = $request->validate([
                    'date_heure' => 'required|date',
                    'statut' => 'required|string',
                    'notes' => 'nullable|string'
                ]);

        $appointment->update($validated);

            return response()->json(['message' => 'Appointment updated successfully']);
    }

    protected function getAvailableTimeSlots(User $dentist)
    {
        $slots = [];
        $daysToShow = 14; 

        for ($i = 0; $i < $daysToShow; $i++) {
            $date = now()->addDays($i);
            $dayOfWeek = strtolower($date->format('l'));

            $workingHours = $dentist->workingHours()
                ->where('jour', $dayOfWeek)
                ->get();

            foreach ($workingHours as $schedule) {
                $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->heure_debut);
                $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->heure_fin);

                while ($startTime->copy()->addMinutes(self::SLOT_DURATION)->lte($endTime)) {
                    $slotDateTime = $startTime->format('Y-m-d H:i:s');
                    
                    if (RendezVous::isTimeSlotAvailable($dentist->id, $slotDateTime)) {
                        $slots[] = $slotDateTime;
                    }
                    
                    $startTime->addMinutes(self::SLOT_DURATION);
                }
            }
        }

        return $slots;
    }    public function calendar(Request $request)
    {
        $dentist = User::with(['dentist', 'workingHours'])
            ->where('role', 'dentist')
            ->findOrFail($request->query('id'));

        $workingHours = $dentist->workingHours()
            ->orderBy('jour')
            ->get();

        return view('appointments.calendar', [
            'dentist' => $dentist,
            'workingHours' => $workingHours
        ]);
    }

    public function show(RendezVous $appointment)
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
        return response()->json($appointment);

    }

    public function destroy(RendezVous $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return redirect()->route('dashboard')->with('success', 'Appointment deleted');
    }    public function cancel(RendezVous $appointment)
    {
        $appointment->update(['statut' => 'annulé']);
        return redirect()->back()->with('success', 'Appointment cancelled successfully');
    }

    public function confirm(RendezVous $appointment)
    {
        $appointment->update(['statut' => 'confirmé']);
        return redirect()->back()->with('success', 'Appointment confirmed');
    }

    public function showRecord(RendezVous $appointment)
    {
        $this->authorize('view', $appointment);

        $medicalRecord = $appointment->dossierMedical;

        return view('appointments.record', [
            'appointment' => $appointment,
            'medicalRecord' => $medicalRecord
        ]);
    }
}