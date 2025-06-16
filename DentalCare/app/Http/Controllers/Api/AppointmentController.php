<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function getAvailableTimeSlots(Request $request, User $dentist)
    {
        if (!$dentist->isDentist()) {
            return response()->json(['error' => 'Dentist not found'], 404);
        }

        $date = Carbon::parse($request->date);
        $dayName = strtolower($date->format('l')); 

        $workingHours = $dentist->dentist->plagesHoraires()
            ->where('jour', $dayName)
            ->get();

        if ($workingHours->isEmpty()) {
            return response()->json([]); 
        }

        $availableSlots = [];
        $slotDuration = 30; 

        foreach ($workingHours as $hours) {
            $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $hours->heure_debut);
            $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $hours->heure_fin);

            while ($startTime->addMinutes($slotDuration)->lte($endTime)) {
                $slotTime = $startTime->format('H:i');
                
                $isBooked = RendezVous::where('dentist_id', $dentist->id)
                    ->whereDate('date_heure', $date)
                    ->whereTime('date_heure', $slotTime)
                    ->exists();

                if (!$isBooked) {
                    $availableSlots[] = $slotTime;
                }
            }
        }

        return response()->json($availableSlots);
    }
}
