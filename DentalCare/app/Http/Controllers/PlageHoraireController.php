<?php

namespace App\Http\Controllers;

use App\Models\PlageHoraire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlageHoraireController extends Controller
{
    public function index()
    {
        $workingHours = PlageHoraire::where('dentist_id', Auth::id())
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get();
            
        return view('working-hours.index', compact('workingHours'));
    }

    public function create()
    {
        return view('working-hours.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jour' => 'required|string',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        $validated['dentist_id'] = Auth::id();

        PlageHoraire::create($validated);

        return redirect()->route('working-hours.index')->with('success', 'Working hours added successfully');
    }

    public function edit(PlageHoraire $plageHoraire)
    {
        return view('working-hours.edit', compact('plageHoraire'));
    }

    public function update(Request $request, PlageHoraire $plageHoraire)
    {
        $validated = $request->validate([
            'jour' => 'required|string',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        $plageHoraire->update($validated);

        return redirect()->route('working-hours.index')->with('success', 'Working hours updated successfully');
    }

    public function destroy(PlageHoraire $plageHoraire)
    {
        $plageHoraire->delete();
        return redirect()->route('working-hours.index')->with('success', 'Working hours deleted successfully');
    }
}
