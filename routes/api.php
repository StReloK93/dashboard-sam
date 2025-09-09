<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransportStateController;
use App\Http\Controllers\ExportController;
use App\Services\WialonService;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
   Route::get('/user', [AuthController::class, 'getUser']);
   Route::get('/logout', [AuthController::class, 'logoutUser']);
});





Route::apiResource('transportstates', TransportStateController::class)->only(['index', 'show', 'update']);
Route::post('states/select_smena', [TransportStateController::class, 'selectSmena']);
// Route::post('states/peresmena-graphic', [TransportStateController::class, 'waitingInOilGraphic']);
Route::post('information/get-park-information', [TransportStateController::class, 'getParkInformation']);



Route::get('transports/excavatorstate', [TripsController::class, 'excavatorState']);
Route::post('transports/car_reports', [TripsController::class, 'carReports']);
Route::post('speeds-by-hour', [TripsController::class, 'getSpeedsByHour']);
Route::get('get-cause-list', [TripsController::class, 'getCauseList']);
Route::post('get-to-excavators', [TripsController::class, 'getToExcavators']);
Route::post('get-to-drillings', [TripsController::class, 'getToDrillings']);


Route::post('export-table-pdf', [ExportController::class, 'exportTablePdf']);
Route::get('export/report/{date}/{weekCount}', [ExportController::class, 'exportReport']);







Route::get('wialon-service/trucks/{radius}', [WialonService::class, 'dumpTrucksPosition']);
Route::get('wialon-service/water-trucks', [WialonService::class, 'waterTrucksPosition']);
Route::get('wialon-service/write-to-db', [WialonService::class, 'writeToDB']);

