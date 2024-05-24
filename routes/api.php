<?php
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripsController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransportStateController;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::apiResource('transportstates', TransportStateController::class)->only(['index', 'show', 'update']);
Route::get('process/excavator', [TransportStateController::class, 'excavator']);
Route::post('states/select_smena', [TransportStateController::class, 'selectSmena']);
Route::post('states/peresmena-graphic', [TransportStateController::class, 'waitingInOilGraphic']);


Route::get('transports/all', [TransportController::class, 'index']);
Route::get('transports/excavators', [TransportController::class, 'excavators']);
Route::get('transports/all/wialon', [TransportController::class, 'getWithWialon']);

Route::get('transports/excavatorstate', [TripsController::class, 'excavatorState']);
Route::post('transports/car_reports', [TripsController::class, 'carReports']);
Route::post('speeds-by-hour', [TripsController::class, 'getSpeedsByHour']);

Route::get('export/report/{date}/{weekCount}', function ($date, $weekCount) {
   return Excel::download(new ReportExport($date, $weekCount), "$date-$weekCount.xlsx");
});


Route::get('information', function () {
   return ['oil' => env('BASE_OIL'), 'smena' => env('BASE_SMENA')];
});

Route::middleware('auth:sanctum')->group(function () {

   Route::get('/user', [AuthController::class, 'getUser']);
   Route::get('/logout', [AuthController::class, 'logoutUser']);

});


// // 9748 55 tn 10013
// // 9749 90 tn
// // 9750 91 tn
// // 9751 92 tn