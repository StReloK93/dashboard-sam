<?php

namespace App\Services;
use Illuminate\Support\Facades\Schema;
use App\Services\WialonService;
use Carbon\Carbon;
use App\Helpers\Smena;
use DB;
class Muruntau
{

   public function writeDb($transports, $tablename)
   {
      if(empty(env('DB_DATABASE_ONLINE'))) return;
      // ReportStaysMekhanizmsLastCordinate
      $models = $this->modelFormatter($transports);

      if(Schema::connection('muruntau')->hasTable($tablename)){
         DB::connection('muruntau')->table($tablename)->delete();
         DB::connection('muruntau')->table($tablename)->insert($models);
      }
   }


   public function modelFormatter($transports)
   {
      $smena = new Smena();
      $excavatorGeozone = collect($transports)->filter(function ($item) {
            return mb_stripos(mb_strtolower($item['geozone']), mb_strtolower(env('BASE_MURUNTAU_TEXT'))) !== false;
      })->all();
      $array = [];

      foreach ($excavatorGeozone as $key => $value) {
         $daySettings = $smena->currentDaySmena(Carbon::parse($value['time_message']));
         $array[$key] = [
            'objectID' => $value['transport_id'],
            'position' => $value['geozone'],
            'smenaDate' => $daySettings['day'],
            'smena' => $daySettings['smena'],
            'idPodrazd' => 1,
            'created' => $value['time_message'],
         ];
      }


      $filtered = array_values($array);
      return $filtered;
   }
}