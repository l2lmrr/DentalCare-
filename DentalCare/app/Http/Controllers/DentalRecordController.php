<?php

namespace App\Http\Controllers;

use App\Models\DossierMedical;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DentalRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole(['praticien', 'admin'])) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $records = DossierMedical::with(['patient', 'praticien'])
            ->where('praticien_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('records.index', compact('records'));
    }

    public function create()
    {
        $patients = User::where('role', 'patient')->get();
        return view('records.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'diagnostic' => 'required|string',
            'traitement' => 'required|string',
            'prescription' => 'required|string',
        ]);

        $validated['praticien_id'] = auth()->id();

        DossierMedical::create($validated);

        return redirect()->route('records.index')
            ->with('success', 'Medical record created successfully.');
    }

    public function show(DossierMedical $record)
    {
        Gate::authorize('view', $record);
        return view('records.show', compact('record'));
    }

    public function edit(DossierMedical $record)
    {
        $this->authorize('update', $record);
        
        $patients = User::where('role', 'patient')->get();
        return view('records.edit', compact('record', 'patients'));
    }

    public function update(Request $request, DossierMedical $record)
    {
        $this->authorize('update', $record);

        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'diagnostic' => 'required|string',
            'traitement' => 'required|string',
            'prescription' => 'required|string',
        ]);

        $record->update($validated);

        return redirect()->route('records.index')
            ->with('success', 'Medical record updated successfully.');
    }

    public function destroy(DossierMedical $record)
    {
        $this->authorize('delete', $record);

        $record->delete();

        return redirect()->route('records.index')
            ->with('success', 'Medical record deleted successfully.');
    }
}