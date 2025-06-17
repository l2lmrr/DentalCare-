<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class DentistAvailabilityController extends Controller
{
    private $workingHours = [
        'monday' => [
            'morning' => ['09:00', '12:30'],
            'afternoon' => ['14:30', '17:00']
        ],
        'tuesday' => [
            'morning' => ['09:00', '12:30'],
            'afternoon' => ['14:30', '17:00']
        ],
        'wednesday' => [
            'morning' => ['09:00', '12:30'],
            'afternoon' => ['14:30', '17:00']
        ],
        'thursday' => [
            'morning' => ['09:00', '12:30'],
            'afternoon' => ['14:30', '17:00']
        ],
        'friday' => [
            'morning' => ['09:00', '12:00'],
            'afternoon' => ['15:00', '17:30']
        ]
    ];

    public function getAvailability(Request $request, $dentistId)
    {
        try {
            Log::debug('Fetching availability', [
                'dentist_id' => $dentistId,
                'date' => $request->date,
                'user' => $request->user()->id ?? 'not logged in'
            ]);

            if (!$request->filled('date')) {
                Log::warning('Missing date parameter in availability request');
                return response()->json([
                    'error' => 'Date parameter is required',
                    'date' => null,
                    'slots' => []
                ], 400);
            }

            $dentist = User::where('id', $dentistId)
                         ->where('role', 'dentist')
                         ->first();

            if (!$dentist) {
                Log::warning('Dentist not found', ['dentist_id' => $dentistId]);
                return response()->json([
                    'error' => 'Dentist not found',
                    'date' => $request->date,
                    'slots' => []
                ], 404);
            }

            $date = Carbon::parse($request->date);
            $dayName = strtolower($date->format('l'));

            if ($date->isPast() && !$date->isToday()) {
                return response()->json([
                    'date' => $date->format('Y-m-d'),
                    'slots' => []
                ]);
            }

            if ($date->isWeekend()) {
                return response()->json([
                    'date' => $date->format('Y-m-d'),
                    'slots' => []
                ]);
            }

            if (!isset($this->workingHours[$dayName])) {
                return response()->json([
                    'date' => $date->format('Y-m-d'),
                    'slots' => []
                ]);
            }

            $bookedSlots = RendezVous::where('dentist_id', $dentistId)
                                   ->whereDate('date_heure', $date)
                                   ->where('statut', '!=', 'annulé')
                                   ->get()
                                   ->map(function($appointment) {
                                       return Carbon::parse($appointment->date_heure)->format('H:i');
                                   })
                                   ->toArray();

            $availableSlots = [];
            $daySchedule = $this->workingHours[$dayName];

            if (isset($daySchedule['morning'])) {
                $start = Carbon::parse($date->format('Y-m-d') . ' ' . $daySchedule['morning'][0]);
                $end = Carbon::parse($date->format('Y-m-d') . ' ' . $daySchedule['morning'][1]);
                
                while ($start < $end) {
                    if (!in_array($start->format('H:i'), $bookedSlots) && 
                        (!$date->isToday() || $start->isFuture())) {
                        $availableSlots[] = [
                            'time' => $start->format('H:i'),
                            'period' => 'morning'
                        ];
                    }
                    $start->addMinutes(30);
                }
            }

            if (isset($daySchedule['afternoon'])) {
                $start = Carbon::parse($date->format('Y-m-d') . ' ' . $daySchedule['afternoon'][0]);
                $end = Carbon::parse($date->format('Y-m-d') . ' ' . $daySchedule['afternoon'][1]);
                
                while ($start < $end) {
                    if (!in_array($start->format('H:i'), $bookedSlots) && 
                        (!$date->isToday() || $start->isFuture())) {
                        $availableSlots[] = [
                            'time' => $start->format('H:i'),
                            'period' => 'afternoon'
                        ];
                    }
                    $start->addMinutes(30);
                }
            }

            return response()->json([
                'date' => $date->format('Y-m-d'),
                'slots' => $availableSlots
            ]);

        } catch (\Exception $e) {
            Log::error('Availability error: ' . $e->getMessage(), [
                'dentist_id' => $dentistId,
                'date' => $request->date ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch availability',
                'date' => $request->date ?? null,
                'slots' => []
            ], 500);
        }
    }
}