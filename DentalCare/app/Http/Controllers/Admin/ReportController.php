<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });    }

    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', 'this_month');
        $reportType = $request->input('report_type', 'appointments');
        
        // Initialize variables
        $startDate = null;
        $endDate = null;
        $reportData = [];
        $title = '';

        // Set date range
        switch ($dateRange) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                $title = 'Today\'s Report';
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $title = 'This Week\'s Report';
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $title = 'This Month\'s Report';
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $title = 'This Year\'s Report';
                break;
            case 'custom':
                $startDate = Carbon::parse($request->input('start_date'));
                $endDate = Carbon::parse($request->input('end_date'));
                $title = 'Custom Date Range Report';
                break;
        }

        // Generate report data based on type
        switch ($reportType) {
            case 'appointments':
                $reportData = $this->generateAppointmentsReport($startDate, $endDate);
                $title = 'Appointments ' . $title;
                break;
            case 'patients':
                $reportData = $this->generatePatientsReport($startDate, $endDate);
                $title = 'Patients ' . $title;
                break;
            case 'revenue':
                $reportData = $this->generateRevenueReport($startDate, $endDate);
                $title = 'Revenue ' . $title;
                break;
        }

        // If export requested
        if ($request->has('export')) {
            return $this->exportReport($reportType, $reportData, $title, $startDate, $endDate);
        }

        return view('admin.reports.index', compact(
            'reportData', 
            'title',
            'dateRange',
            'reportType',
            'startDate',
            'endDate'
        ));
    }

    protected function generateAppointmentsReport($startDate, $endDate)
    {
        $appointments = RendezVous::with(['patient', 'praticien'])
            ->whereBetween('date_heure', [$startDate, $endDate])
            ->orderBy('date_heure')
            ->get();

        $statusCounts = RendezVous::whereBetween('date_heure', [$startDate, $endDate])
            ->selectRaw('statut, count(*) as count')
            ->groupBy('statut')
            ->pluck('count', 'statut');

        $dentists = User::where('role', 'praticien')
            ->withCount(['dentistAppointments' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('date_heure', [$startDate, $endDate]);
            }])
            ->orderBy('dentist_appointments_count', 'desc')
            ->get();

        return [
            'appointments' => $appointments,
            'statusCounts' => $statusCounts,
            'dentists' => $dentists,
            'total' => $appointments->count(),
        ];
    }

    protected function generatePatientsReport($startDate, $endDate)
    {
        $newPatients = User::where('role', 'patient')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $activePatients = RendezVous::whereBetween('date_heure', [$startDate, $endDate])
            ->distinct('patient_id')
            ->count('patient_id');

        $patientsByMonth = User::where('role', 'patient')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'newPatients' => $newPatients,
            'activePatients' => $activePatients,
            'patientsByMonth' => $patientsByMonth,
        ];
    }

    protected function generateRevenueReport($startDate, $endDate)
    {
        // This assumes you have payment information in your database
        // Adjust according to your actual payment structure
        $revenueByMonth = RendezVous::whereBetween('date_heure', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(date_heure, "%Y-%m") as month, sum(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $revenueByService = RendezVous::whereBetween('date_heure', [$startDate, $endDate])
            ->selectRaw('service_type, sum(amount) as total')
            ->groupBy('service_type')
            ->orderBy('total', 'desc')
            ->get();

        $totalRevenue = $revenueByMonth->sum('total');

        return [
            'revenueByMonth' => $revenueByMonth,
            'revenueByService' => $revenueByService,
            'totalRevenue' => $totalRevenue,
        ];
    }

    protected function exportReport($type, $data, $title, $startDate, $endDate)
    {
        $pdf = PDF::loadView('admin.reports.exports.' . $type, [
            'data' => $data,
            'title' => $title,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);

        $filename = strtolower(str_replace(' ', '_', $title)) . '_' . now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }
}