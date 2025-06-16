<?php

namespace App\Http\Controllers;

use App\Models\DossierMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function medicalRecords()
    {        $medicalRecords = DossierMedical::where('patient_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('patient.medical-records', compact('medicalRecords'));
    }
}
