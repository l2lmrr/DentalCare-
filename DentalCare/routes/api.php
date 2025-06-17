<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DentistAvailabilityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    // Dentist Availability Endpoint
    Route::get('/dentists/{dentist}/availability', [DentistAvailabilityController::class, 'getAvailability'])
        ->name('api.dentists.availability')
        ->where('dentist', '[0-9]+');
});

