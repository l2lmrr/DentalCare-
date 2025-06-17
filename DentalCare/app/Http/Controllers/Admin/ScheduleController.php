<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PlageHoraire;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = PlageHoraire::with('dentist')
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get()
            ->map(function ($schedule) {
                $startTime = Carbon::parse($schedule->heure_debut);
                $endTime = Carbon::parse($schedule->heure_fin);
                $duration = $startTime->diffInMinutes($endTime);
                
                return [
                    'id' => $schedule->id,
                    'dentist' => $schedule->dentist,
                    'day' => $schedule->getDayNameAttribute(),
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $endTime->format('H:i'),
                    'duration' => floor($duration / 60) . 'h ' . ($duration % 60) . 'min'
                ];
            });

        $dentists = User::where('role', 'praticien')->get();
        
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday'
        ];

        return view('admin.schedule.index', compact('schedules', 'dentists', 'days'));
    }

    public function destroy(PlageHoraire $schedule)
    {
        try {
            $schedule->delete();
            return redirect()->route('admin.schedule')
                ->with('success', 'Schedule deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.schedule')
                ->with('error', 'Failed to delete schedule');
        }
    }

}
