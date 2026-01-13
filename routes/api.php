<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\WedstrijdController;
use App\Http\Controllers\StatistiekenController;

// Teams routes
Route::get('/teams', [TeamController::class, 'index']);
Route::post('/teams', [TeamController::class, 'store']);
Route::get('/teams/{id}', [TeamController::class, 'show']);
Route::put('/teams/{id}', [TeamController::class, 'update']);
Route::delete('/teams/{id}', [TeamController::class, 'destroy']);

// Players routes
Route::get('/players', [PlayerController::class, 'index']);
Route::post('/players', [PlayerController::class, 'store']);
Route::get('/players/{id}', [PlayerController::class, 'show']);
Route::put('/players/{id}', [PlayerController::class, 'update']);
Route::delete('/players/{id}', [PlayerController::class, 'destroy']);

// Trainings routes (API resource)
Route::apiResource('trainings', TrainingController::class);
Route::post('/trainings/{training}/attendance', [TrainingController::class, 'updateAttendance']);

// Wedstrijden routes - specificeer de parameter naam als 'wedstrijd' (enkelvoud)
Route::apiResource('wedstrijden', WedstrijdController::class)->parameters([
    'wedstrijden' => 'wedstrijd'
]);


Route::get('/statistieken', [StatistiekenController::class, 'apiIndex']);

