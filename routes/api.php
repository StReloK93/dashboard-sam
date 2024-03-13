<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripsController;
use App\Http\Controllers\GeofenceController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportStateController;
use App\Services\WialonService;
use App\Models\Truck;
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


Route::get('/hayyaro', function () {
   $wia = new WialonService();

   // $groups = $wia->getGroups();
   // $olddan = $wia->getTransportPoints(10013);
   // $eks = $wia->getTransportPoints(4076);
   // $collection = collect($olddan)->merge($eks);
   // dd($collection->all());

   // foreach ($groups['items'] as $key => $group) {
   //    if (in_array($group['id'], $list)) {

   //       foreach ($group['u'] as $key => $value) {
   //          Truck::updateOrCreate(
   //             ['transport_id' => $value],
   //             ['group_name' => $group['nm'],'group_id' => $group['id']]
   //          );
   //       }

   //    }
   // }



});

// // 9748 55 tn 10013
// // 9749 90 tn
// // 9750 91 tn
// // 9751 92 tn