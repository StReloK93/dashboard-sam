<?php
namespace App\Services;

class GeoZoneService
{

   public function findZone($point, $zones)
   {
      $issue = null;
      foreach ($zones as $zone) {
         if (count($zone['points']) == 1) {
            $distance = $this->calculateDistance($point, $zone['points'][0]);
            if ($distance <= $zone['points'][0]['r']) {
               $issue = $zone['name'];
               break;
            }
         } else {
            $contain = $this->pointInPolygon($point, $zone['points']);
            if ($contain) {
               $issue = $zone['name'];
               break;
            }
         }
      }

      return $issue;
   }

   public function getDistances($point, $transports)
   {
      $distances = [];
      foreach ($transports as $key => $car) {
         $distances[$key]['distance'] = $this->calculateDistance($point, ['x' => $car['x'] , 'y' => $car['y']]);
         $distances[$key]['name'] = $car['name'];
      }
      sort($distances);
      return $distances;
   }

   function calculateDistance($point1, $point2)
   {
      // Радиус Земли в километрах
      $earthRadius = 6371.0;

      // Преобразование координат из градусов в радианы
      $lat1 = deg2rad($point1['y']);
      $lon1 = deg2rad($point1['x']);
      $lat2 = deg2rad($point2['y']);
      $lon2 = deg2rad($point2['x']);

      // Разница между широтами и долготами
      $dlat = $lat2 - $lat1;
      $dlon = $lon2 - $lon1;

      // Формула гаверсинусов
      $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

      $distanceInKilometers = $earthRadius * $c;

      // Расстояние между точками в метрах
      $distanceInMeters = $distanceInKilometers * 1000;

      return $distanceInMeters;
   }

   function pointInPolygon($point, $polygon)
   {
      if ($polygon[0] != $polygon[count($polygon) - 1])
         $polygon[count($polygon)] = $polygon[0];
         
      $j = 0;
      $oddNodes = false;
      $x = $point['x'];
      $y = $point['y'];
      $n = count($polygon);
      for ($i = 0; $i < $n; $i++) {
         $j++;
         if ($j == $n) {
            $j = 0;
         }
         if (
            (($polygon[$i]['y'] < $y) && ($polygon[$j]['y'] >= $y)) || (($polygon[$j]['y'] < $y) && ($polygon[$i]['y'] >=
               $y))
         ) {
            if (
               $polygon[$i]['x'] + ($y - $polygon[$i]['y']) / ($polygon[$j]['y'] - $polygon[$i]['y']) * ($polygon[$j]['x'] -
                  $polygon[$i]['x']) < $x
            ) {
               $oddNodes = !$oddNodes;
            }
         }
      }
      return $oddNodes;
   }

}