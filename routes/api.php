<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripsController;
use App\Http\Controllers\GeofenceController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportStateController;

Route::get('process/all', [TripsController::class, 'index']);
Route::get('geofences/all', [GeofenceController::class, 'index']);

Route::apiResource('transportstates', TransportStateController::class)->only(['index', 'show']);

Route::get('transports/all', [TransportController::class, 'index']);
Route::get('transports/excavators', [TransportController::class, 'excavators']);
Route::get('transports/all/wialon', [TransportController::class, 'getWithWialon']);

// BCG Скоростная карта

// // 9748 55 tn
// // 9749 90 tn
// // 9750 91 tn
// // 9751 92 tn