<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DentistAvailabilityController extends Controller
{
    public function index(Dentist $dentist, Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);
        
        $date = Carbon::parse($request->date);
        $dayOfWeek = strtolower($date->isoFormat('dddd'));
        
        // Get working hours for this day
        $workingHours = json_decode($dentist->working_hours, true)[$dayOfWeek] ?? null;
        
        if (!$workingHours) {
            return response()->json([]);
        }
        
        // Generate time slots (30-minute intervals)
        $start = Carbon::parse($workingHours['start']);
        $end = Carbon::parse($workingHours['end']);
        $slots = [];
        
        while ($start->lt($end)) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(30);
        }
        
        // Filter out booked slots
        $bookedSlots = $dentist->appointments()
            ->whereDate('date_heure', $date)
            ->pluck('date_heure')
            ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
            ->toArray();
            
        $availableSlots = array_diff($slots, $bookedSlots);
        
        return response()->json(array_values($availableSlots));
    }
}