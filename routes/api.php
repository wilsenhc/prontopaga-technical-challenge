<?php

use App\Http\Controllers\AppointmentPayController;
use App\Http\Controllers\AppointmentsConfirmController;
use App\Http\Controllers\AppointmentsIndexController;
use App\Http\Controllers\AppointmentStoreController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)->name('register');

Route::post('/login', LoginController::class)->name('login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/appointments', AppointmentsIndexController::class)
        ->name('appointments.index')
        ->middleware('can:viewAny,App\Models\Appointment');

    Route::post('/appointments', AppointmentStoreController::class)->name('appointments.store');

    Route::post('/appointments/pay', AppointmentPayController::class)
        ->name('appointments.pay')
        ->where('appointment', '[0-9]+');

    Route::post('/appointments/confirm', AppointmentsConfirmController::class)
        ->name('appointments.confirm')
        ->middleware('can:viewAny,App\Models\Appointment');
});
