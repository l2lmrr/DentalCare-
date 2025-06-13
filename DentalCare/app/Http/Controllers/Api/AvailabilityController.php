<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RendezVous;
use App\Models\PlageHoraire;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    private const MORNING_START = '09:00';
    private const MORNING_END = '12:30';
    private const AFTERNOON_START = '15:00';
    private const AFTERNOON_END = '17:00';
    private const SLOT_DURATION = 30; // minutes

    public function getTimeSlots(Request $request, User $dentist)
    {
        if (!$dentist->isDentist()) {
            return response()->json(['error' => 'Dentist not found'], 404);
        }

        $date = Carbon::parse($request->date);
        
        if ($date->isPast()) {
            return response()->json([]);
        }

        if ($date->isWeekend()) {
            return response()->json([]);
        }

        // Get working hours for this dentist and day
        $workingHours = $dentist->workingHours()
            ->where('jour', strtolower($date->format('l')))
            ->get();

        if ($workingHours->isEmpty()) {
            return response()->json([]);
        }

        $availableSlots = [];
        foreach ($workingHours as $hours) {
            $start = Carbon::parse($date->format('Y-m-d') . ' ' . $hours->heure_debut);
            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $hours->heure_fin);
            $slotDuration = 30; // minutes

            while ($start->copy()->addMinutes($slotDuration)->lte($end)) {
                $availableSlots[] = $start->format('H:i');
                $start->addMinutes($slotDuration);
            }
        }

        // Get booked slots
        $bookedSlots = RendezVous::where('dentist_id', $dentist->id)
            ->whereDate('date_heure', $date)
            ->where('statut', '!=', 'annulé')
            ->get()
            ->map(function ($appointment) {
                return Carbon::parse($appointment->date_heure)->format('H:i');
            })
            ->toArray();

        // Remove booked slots from available slots
        $availableSlots = array_values(array_diff($availableSlots, $bookedSlots));
        
        return response()->json($availableSlots);
    }
}
