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
    }    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'diagnostic' => 'required|string|min:3',
            'traitement' => 'required|string|min:3',
            'prescription' => 'required|string|min:3',
        ]);

        try {
            DossierMedical::create([
                'patient_id' => $validated['patient_id'],
                'dentist_id' => Auth::id(),
                'diagnostic' => $validated['diagnostic'],
                'traitement' => $validated['traitement'],
                'prescription' => $validated['prescription'],
            ]);

            return response()->json([
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false
            ], 500);
        }
    }

    public function show(DossierMedical $medicalRecord)
    {
        return view('modals.view-medical-record', compact('medicalRecord'));
    }

    public function edit(DossierMedical $medicalRecord)
    {
        return view('modals.medical-records-edit', compact('medicalRecord'));
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
    }    public function getPatientRecords($patientId)
    {
        try {
            $patient = User::findOrFail($patientId);
            
            // Check if the current user is authorized to view these records
            if (!Auth::user()->role === 'dentist' && Auth::id() !== $patientId) {
                abort(403, 'Unauthorized');
            }
            
            $records = DossierMedical::where('patient_id', $patientId)
                ->with(['dentist'])
                ->orderBy('created_at', 'desc')
                ->paginate(10); // Add pagination here
                
            return view('modals.patient-records', [
                'records' => $records,
                'patient' => $patient
            ]);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Failed to load medical records'], 500);
            }
            return back()->with('error', 'Failed to load medical records');
        }
    }
}