<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripsController;
use App\Http\Controllers\GeofenceController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportStateController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('process/all', [TripsController::class, 'index']);
Route::get('geofences/all', [GeofenceController::class, 'index']);

Route::apiResource('transportstates', TransportStateController::class)->only(['index', 'show']);

Route::get('transports/all', [TransportController::class, 'index']);
Route::get('transports/excavators', [TransportController::class, 'excavators']);
Route::get('transports/all/wialon', [TransportController::class, 'getWithWialon']);

// BCG Скоростная карта