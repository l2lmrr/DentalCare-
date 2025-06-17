<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PlageHoraireController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth Routes
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Homepage and Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dentist Listing (Public)
Route::get('/dentists', [DentistController::class, 'index'])->name('dentist.index');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Dashboard redirect for all users
    Route::get('/dashboard', [DashboardController::class, 'redirectToDashboard'])->name('dashboard');
    
    // Specific dashboard routes
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/dentist/dashboard', [DentistController::class, 'dashboard'])->name('dentist.dashboard');
    Route::get('/patient/dashboard', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Public routes
    Route::get('/dentists', [DentistController::class, 'index'])->name('dentist.index');
    
    // Appointments Routes
    Route::prefix('appointments')->name('appointments.')->middleware('auth')->group(function () {
        Route::get('/calendar', [AppointmentController::class, 'calendar'])->name('calendar');
        Route::get('/my', [AppointmentController::class, 'index'])->name('my');
        Route::post('/store', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::patch('/{appointment}', [AppointmentController::class, 'update'])->name('update');
        Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
        Route::post('/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('confirm');
    });

    // Patient Routes
    Route::middleware('role:patient')->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
        Route::get('/patient/medical-records', [PatientController::class, 'medicalRecords'])->name('patient.medical-records');
    });

    // Dentist Routes
    Route::middleware('role:dentist')->group(function () {
        Route::get('/dentist/dashboard', [DentistController::class, 'dashboard'])->name('dentist.dashboard');
        Route::get('/dentist/appointments', [DentistController::class, 'appointments'])->name('dentist.appointments');
        Route::get('/dentist/schedule', [DentistController::class, 'schedule'])->name('dentist.schedule');
        Route::get('/dentist/patients', [DentistController::class, 'patients'])->name('dentist.patients');
    });

    // Dentist and Admin appointment management
    Route::middleware('role:praticien,admin')->group(function () {
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    // Medical Records Routes (for dentists and admins)
    Route::middleware('role:praticien,admin')->prefix('medical-records')->group(function () {
        Route::get('/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
        Route::post('/', [MedicalRecordController::class, 'store'])->name('medical-records.store');
        Route::get('/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('medical-records.show');
        Route::get('/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('medical-records.edit');
        Route::patch('/{medicalRecord}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
        Route::delete('/{medicalRecord}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');
    });

    // Working Hours Management (for dentists and admins)
    Route::middleware('role:praticien,admin')->prefix('working-hours')->group(function () {
        Route::get('/', [\App\Http\Controllers\PlageHoraireController::class, 'index'])->name('working-hours.index');
        Route::get('/create', [\App\Http\Controllers\PlageHoraireController::class, 'create'])->name('working-hours.create');
        Route::post('/', [\App\Http\Controllers\PlageHoraireController::class, 'store'])->name('working-hours.store');
        Route::get('/{plageHoraire}/edit', [\App\Http\Controllers\PlageHoraireController::class, 'edit'])->name('working-hours.edit');
        Route::patch('/{plageHoraire}', [\App\Http\Controllers\PlageHoraireController::class, 'update'])->name('working-hours.update');
        Route::delete('/{plageHoraire}', [\App\Http\Controllers\PlageHoraireController::class, 'destroy'])->name('working-hours.destroy');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        
        // Dentist Management
        Route::get('/dentists', [App\Http\Controllers\Admin\AdminController::class, 'manageDentists'])->name('dentists');
        Route::post('/dentists', [App\Http\Controllers\Admin\AdminController::class, 'storeDentist'])->name('dentists.store');
        Route::put('/dentists/{dentist}', [App\Http\Controllers\Admin\AdminController::class, 'updateDentist'])->name('dentists.update');
        Route::post('/dentists/{dentist}/toggle', [App\Http\Controllers\Admin\AdminController::class, 'toggleDentistStatus'])->name('dentists.toggle');
        
        // Working Hours Management
        Route::get('/schedule', [App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedule');
        Route::post('/schedule', [App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('schedule.store');
        Route::delete('/schedule/{schedule}', [App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedule.delete');
        
        // Appointments Overview
        Route::get('/appointments', [App\Http\Controllers\Admin\AdminController::class, 'appointments'])->name('appointments');
        Route::put('/appointments/{appointment}/cancel', [App\Http\Controllers\Admin\AdminController::class, 'cancelAppointment'])->name('appointments.cancel');
    });

    // Appointment routes
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('patient.appointments');


});    // Calendar and Patient Records Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/calendar/{dentist}', [AppointmentController::class, 'calendar'])
            ->name('calendar.show')
            ->where('dentist', '[0-9]+');
            
        // Patient Records Route
        Route::get('/patients/{patient}/records', [MedicalRecordController::class, 'getPatientRecords'])
            ->name('patients.records')
            ->where('patient', '[0-9]+');
    });

    // Dentist Availability Routes
    Route::get('/api/dentists/{dentist}/availability', [App\Http\Controllers\Api\DentistAvailabilityController::class, 'getAvailability'])
        ->name('api.dentists.availability')
        ->where('dentist', '[0-9]+');

// Fallback Route (404 Page)
Route::fallback(function () {
    return view('errors.404');
});