<?php


use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripsController;
use App\Http\Controllers\GeofenceController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransportStateController;
use App\Helpers\Smena;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);



Route::get('process/all', [TripsController::class, 'index']);
Route::get('geofences/all', [GeofenceController::class, 'index']);

Route::apiResource('transportstates', TransportStateController::class)->only(['index', 'show', 'update']);
Route::get('process/excavator', [TransportStateController::class, 'excavator']);
Route::post('states/select_smena', [TransportStateController::class, 'selectSmena']);
Route::post('states/peresmena-graphic', [TransportStateController::class, 'peresmenaGraphic']);



Route::get('transports/all', [TransportController::class, 'index']);
Route::get('transports/excavators', [TransportController::class, 'excavators']);
Route::get('transports/excavatorstate', [TransportController::class, 'excavatorState']);
Route::get('transports/all/wialon', [TransportController::class, 'getWithWialon']);
Route::post('transports/car_reports', [TransportController::class, 'carReports']);


Route::get('export/report/{date}', function ($date) {
   return Excel::download(new ReportExport($date), 'users.xlsx');
});

Route::post('smena-info', function (Request $req) {
   $date = Carbon::parse($req->date);
   $_smena = new Smena();
   return $_smena->getPeriod($date);
});
Route::middleware('auth:sanctum')->group(function () {

   Route::get('/user', [AuthController::class, 'getUser']);
   Route::get('/logout', [AuthController::class, 'logoutUser']);

});


// // 9748 55 tn 10013
// // 9749 90 tn
// // 9750 91 tn
// // 9751 92 tn