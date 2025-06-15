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
    use AuthorizesRequests;// Constants
    const SLOT_DURATION = 30; // minutes

    public function __construct()
    {
        // Remove any auth middleware to match admin functionality
        // $this->middleware('auth');
    }

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

        // Check if dentist is available at this time
        $isWithinWorkingHours = $dentist->workingHours()
            ->where('jour', $dayOfWeek)
            ->where('heure_debut', '<=', $time)
            ->where('heure_fin', '>=', $time)
            ->exists();

        if (!$isWithinWorkingHours) {
            return back()->withErrors(['date_heure' => 'Selected time is outside of dentist\'s working hours'])->withInput();
        }

        // Check for existing appointments
        if (!RendezVous::isTimeSlotAvailable($dentist->id, $validated['date_heure'])) {
            return back()->withErrors(['date_heure' => 'This time slot is already booked'])->withInput();
        }

        // Create the appointment
        $appointment = RendezVous::create([
            'patient_id' => auth()->id(),
            'dentist_id' => $validated['dentist_id'],
            'date_heure' => $validated['date_heure'],
            'statut' => 'confirmé',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Appointment booked successfully!');
    }

    public function edit(RendezVous $appointment)
    {
        try {
            \Log::info('Edit appointment request', ['appointment_id' => $appointment->id]);
            
            // Check if appointment is in the past
            if ($appointment->date_heure->isPast()) {
                return response()->json([
                    'error' => 'Cannot edit past appointments'
                ], 403);
            }

            // Get available time slots
            $availableSlots = $this->getAvailableTimeSlots($appointment->dentist->user);
            
            // Add current appointment time to available slots if it's not in the list
            if (!in_array($appointment->date_heure->format('Y-m-d H:i:s'), $availableSlots)) {
                $availableSlots[] = $appointment->date_heure->format('Y-m-d H:i:s');
                sort($availableSlots);
            }

            // Get dentist's working hours
            $workingHours = PlageHoraire::where('dentist_id', $appointment->dentist_id)
                ->orderBy('jour')
                ->get();

            $view = view('appointments.edit', [
                'appointment' => $appointment,
                'workingHours' => $workingHours,
                'availableSlots' => $availableSlots
            ])->render();

            return response()->json([
                'html' => $view,
                'appointment' => $appointment->toArray(),
                'availableSlots' => $availableSlots,
                'success' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in edit appointment', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to load appointment data',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }    public function update(Request $request, RendezVous $appointment)
    {
        try {
            // Check if appointment is in the past
            if ($appointment->date_heure->isPast()) {
                return response()->json([
                    'error' => 'Cannot edit past appointments'
                ], 403);
            }

            $validated = $request->validate([
                'date_heure' => 'required|date|after:now',
                'notes' => 'nullable|string|max:1000',
                'statut' => 'required|in:confirmé,annulé,reporté',
            ]);

            // Check if the new time slot is available (ignore current appointment)
            if (!RendezVous::isTimeSlotAvailable($appointment->dentist_id, $validated['date_heure'], $appointment->id)) {
                return response()->json([
                    'error' => 'This time slot is already booked'
                ], 422);
            }

            $appointment->update($validated);
            
            return response()->json([
                'message' => 'Appointment updated successfully',
                'appointment' => $appointment->fresh(),
                'success' => true
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update appointment',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function getAvailableTimeSlots(User $dentist)
    {
        $slots = [];
        $daysToShow = 14; // Show availability for next 14 days

        for ($i = 0; $i < $daysToShow; $i++) {
            $date = now()->addDays($i);
            $dayOfWeek = strtolower($date->format('l'));

            $workingHours = $dentist->workingHours()
                ->where('jour', $dayOfWeek)
                ->get();

            foreach ($workingHours as $schedule) {
                $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->heure_debut);
                $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->heure_fin);

                // Generate slots
                while ($startTime->copy()->addMinutes(self::SLOT_DURATION)->lte($endTime)) {
                    $slotDateTime = $startTime->format('Y-m-d H:i:s');
                    
                    // Check if slot is available
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
        // Find the dentist user and load their relationships
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