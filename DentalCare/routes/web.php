<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PlageHoraireController;
use App\Http\Controllers\Api\AvailabilityController;

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
    });

    // Patient Routes
    Route::middleware('role:patient')->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
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
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
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
        Route::get('/', [PlageHoraireController::class, 'index'])->name('working-hours.index');
        Route::get('/create', [PlageHoraireController::class, 'create'])->name('working-hours.create');
        Route::post('/', [PlageHoraireController::class, 'store'])->name('working-hours.store');
        Route::get('/{plageHoraire}/edit', [PlageHoraireController::class, 'edit'])->name('working-hours.edit');
        Route::patch('/{plageHoraire}', [PlageHoraireController::class, 'update'])->name('working-hours.update');
        Route::delete('/{plageHoraire}', [PlageHoraireController::class, 'destroy'])->name('working-hours.destroy');
    });

    // Admin-only Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        
        // System Settings
        Route::get('/settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
        Route::patch('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/appointments', [ReportController::class, 'appointments'])->name('admin.reports.appointments');
        Route::get('/reports/patients', [ReportController::class, 'patients'])->name('admin.reports.patients');
    });

    // Appointment routes
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('patient.appointments');

    // Dentist Routes
    Route::middleware(['auth', 'role:dentist'])->group(function () {
        Route::get('/dentist/dashboard', [DentistController::class, 'dashboard'])->name('dentist.dashboard');
        Route::get('/dentist/appointments', [AppointmentController::class, 'dentistAppointments'])->name('dentist.appointments');
        Route::get('/dentist/patients', [DentistController::class, 'patients'])->name('dentist.patients');
        Route::get('/dentist/calendar', [DentistController::class, 'calendar'])->name('dentist.calendar');
    });
});

// Fallback Route (404 Page)
Route::fallback(function () {
    return view('errors.404');
});