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
Route::get('process/redcolumn/{transport_id}', [TransportStateController::class, 'redColumn']);
Route::post('states/select_smena', [TransportStateController::class, 'selectSmena']);
Route::post('states/peresmena-graphic', [TransportStateController::class, 'waitingInOilGraphic']);
Route::post('information/get-park-information', [TransportStateController::class, 'getParkInformation']);

Route::post('export-table-pdf', [TransportStateController::class, 'exportTablePdf']);


Route::get('transports/all', [TransportController::class, 'index']);
Route::get('transports/excavators', [TransportController::class, 'excavators']);
Route::get('transports/wialon', [TransportController::class, 'getWithWialon']);
Route::get('transports/geozones', [TransportController::class, 'geozones']);
Route::get('transports/account', [TransportController::class, 'account']);
Route::get('transports/getGroups', [TransportController::class, 'getGroups']);
Route::get('transports/getGroupUnitsWithName', [TransportController::class, 'getGroupUnitsWithName']);
Route::get('transports/getDumpTrucks', [TransportController::class, 'getDumpTrucks']);
Route::get('transports/getTransportPoints', [TransportController::class, 'getTransportPoints']);

Route::get('transports/getMessagesInterval', [TransportController::class, 'getMessagesInterval']);

Route::get('transports/write-db', [TransportController::class, 'writeToDB']);



Route::get('transports/excavatorstate', [TripsController::class, 'excavatorState']);
Route::post('transports/car_reports', [TripsController::class, 'carReports']);
Route::post('speeds-by-hour', [TripsController::class, 'getSpeedsByHour']);
Route::get('get-cause-list', [TripsController::class, 'getCauseList']);
Route::post('get-to-excavators', [TripsController::class, 'getToExcavators']);
Route::post('get-to-drillings', [TripsController::class, 'getToDrillings']);



Route::get('export/report/{date}/{weekCount}', function ($date, $weekCount) {
   return Excel::download(new ReportExport($date, $weekCount), "$date-$weekCount.xlsx");
});


// Route::get('information', function () {
//    return ;
// });

Route::middleware('auth:sanctum')->group(function () {

   Route::get('/user', [AuthController::class, 'getUser']);
   Route::get('/logout', [AuthController::class, 'logoutUser']);

});


// // 9748 55 tn 10013
// // 9749 90 tn
// // 9750 91 tn
// // 9751 92 tn