<?php
namespace App\Helpers;

class GPSAnalyzer
{
   private $distanceThreshold; // Порог расстояния в метрах
   private $timeFormat;        // Формат времени для вывода
   private $minStopDuration;   // Минимальная длительность остановки в минутах

   public function __construct($distanceThreshold = 10, $minStopDuration = 1, $timeFormat = 'Y-m-d H:i:s')
   {
      $this->distanceThreshold = $distanceThreshold;
      $this->timeFormat = $timeFormat;
      $this->minStopDuration = $minStopDuration;
   }

   /**
    * Основной метод для анализа GPS-данных.
    */
   public function analyze(array $gpsData): array
   {
      $stops = [];
      $currentStop = [];

      foreach ($gpsData as $index => $point) {
         if ($index === 0) {
            $currentStop[] = $point;
            continue;
         }

         $prevPoint = $gpsData[$index - 1];
         $distance = $this->calculateDistance(
            $prevPoint['pos']['y'],
            $prevPoint['pos']['x'],
            $point['pos']['y'],
            $point['pos']['x']
         );

         if ($distance <= $this->distanceThreshold) {
            $currentStop[] = $point;
         } else {
            if (count($currentStop) > 1) {
               $stop = $this->calculateStopDuration($currentStop);
               if ($stop['duration_minutes'] >= $this->minStopDuration) {
                  $stops[] = $stop;
               }
            }
            $currentStop = [$point];
         }
      }

      if (count($currentStop) > 1) {
         $stop = $this->calculateStopDuration($currentStop);
         if ($stop['duration_minutes'] >= $this->minStopDuration) {
            $stops[] = $stop;
         }
      }

      return $stops;
   }

   /**
    * Рассчитывает расстояние между двумя GPS-точками (формула гаверсина).
    */
   private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
   {
      $earthRadius = 6371000; // Радиус Земли в метрах

      $latDelta = deg2rad($lat2 - $lat1);
      $lonDelta = deg2rad($lon2 - $lon1);

      $a = sin($latDelta / 2) ** 2 +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;

      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

      return $earthRadius * $c;
   }

   /**
    * Рассчитывает продолжительность остановки.
    */
   private function calculateStopDuration(array $stopPoints): array
   {
      $startTime = $stopPoints[0]['t'];
      $endTime = end($stopPoints)['t'];
      $duration = ($endTime - $startTime) / 60; // В минутах

      return [
         'start_time' => date($this->timeFormat, $startTime),
         'end_time' => date($this->timeFormat, $endTime),
         'duration_minutes' => $duration,
         'location' => [
            'latitude' => $stopPoints[0]['pos']['y'],
            'longitude' => $stopPoints[0]['pos']['x'],
         ],
      ];
   }
}