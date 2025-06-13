<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DentistAvailabilityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Api\AvailabilityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

# Protected API Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dentists/{dentist}/availability', [AvailabilityController::class, 'getTimeSlots'])
        ->where('dentist', '[0-9]+')
        ->name('api.dentists.availability');
        
    Route::post('/appointments', [AppointmentController::class, 'store'])
        ->name('api.appointments.store');
        
    Route::get('/availability/{dentist}/{date}', [AvailabilityController::class, 'getAvailableSlots']);
});