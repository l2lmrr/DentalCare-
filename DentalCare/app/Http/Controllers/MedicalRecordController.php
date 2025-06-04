<?php

namespace App\Http\Controllers;

use App\Models\DossierMedical;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function create(Request $request)
    {
        $patient = User::findOrFail($request->patient_id);
        return view('modals.create-medical-record', compact('patient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'diagnostic' => 'required|string',
            'traitement' => 'required|string',
            'prescription' => 'required|string',
        ]);

        DossierMedical::create([
            'patient_id' => $validated['patient_id'],
            'dentist_id' => Auth::id(),
            'diagnostic' => $validated['diagnostic'],
            'traitement' => $validated['traitement'],
            'prescription' => $validated['prescription'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Medical record created');
    }

    public function show(DossierMedical $medicalRecord)
    {
        return view('modals.view-medical-record', compact('medicalRecord'));
    }

    public function edit(DossierMedical $medicalRecord)
    {
        return view('medical-records.edit', compact('medicalRecord'));
    }

    public function update(Request $request, DossierMedical $medicalRecord)
    {
        $validated = $request->validate([
            'diagnostic' => 'required|string',
            'traitement' => 'required|string',
            'prescription' => 'required|string',
        ]);

        $medicalRecord->update($validated);

        return redirect()->route('medical-records.show', $medicalRecord)->with('success', 'Medical record updated');
    }

    public function destroy(DossierMedical $medicalRecord)
    {
        $medicalRecord->delete();
        return redirect()->route('dashboard')->with('success', 'Medical record deleted');
    }
}