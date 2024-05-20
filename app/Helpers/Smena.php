<?php

namespace App\Helpers;

use Carbon\Carbon;

class Smena
{
   public function getPeriod($currentTime)
   {
      $startOfDay = $currentTime->copy()->startOfDay();
      $ninePoint = $startOfDay->copy()->addHours(9)->addMinutes(10);
      $twentyOnePoint = $startOfDay->copy()->addHours(21)->addMinutes(10);
      if ($ninePoint <= $currentTime && $currentTime < $twentyOnePoint) {
         $smena = 1;
         $start = $startOfDay->addHours(9);
         $start->addMinutes(10);

         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      } elseif ($currentTime >= $twentyOnePoint) {
         $smena = 2;
         $start = $startOfDay->addHours(21);
         $start->addMinutes(10);

         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      } else {
         $smena = 2;
         $start = $startOfDay->subDay()->addHours(21);
         $start->addMinutes(10);

         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      }

      return ['smena' => $smena, 'start' => $start, 'end' => $end];
   }



   public function getSmena($date, $smena)
   {
      $point = Carbon::parse($date)->setTimezone('Asia/Tashkent');
      $startOfDay = $point->copy()->startOfDay();
      $startOfDay->addMinutes(10);


      if ($smena == 1) {
         $start = $startOfDay->addHours(9);
         $end = $start->copy()->addHour(12);
      } else {
         $start = $startOfDay->addHours(21);
         $end = $start->copy()->addHour(12);
      }

      return ['smena' => $smena, 'start' => $start, 'end' => $end];
   }


   public function timeBetween($currentTime, $start, $end)
   {
      if (strtotime($currentTime) > strtotime($start) && strtotime($currentTime) < strtotime($end)) {
         return true;
      } else {
         return false;
      }
   }

   public function secondDiff($firstDate, $secondDate)
   {
      $date1 = strtotime($firstDate);
      $date2 = strtotime($secondDate);

      return abs($date2 - $date1);
   }


   public function secondDiffWithoutSmena($firstDate, $secondDate)
   {
      $date1 = strtotime($firstDate);
      $date2 = strtotime($secondDate);

      $daySmenaStart = date('Y-m-d 09:10:00', $date1);
      $daySmenaEnd = date('Y-m-d 09:40:00', $date1);

      $nightSmenaStart = date('Y-m-d 21:10:00', $date1);
      $nightSmenaEnd = date('Y-m-d 21:40:00', $date1);


      if(strtotime($daySmenaStart) < $date2 && $date2 < strtotime($daySmenaEnd) ){
         return 0;
      }

      if(strtotime($nightSmenaStart) < $date2 && $date2 < strtotime($nightSmenaEnd) ){
         return 0;
      }

      $time = 0;
      if($this->timeBetween($daySmenaEnd, $firstDate, $secondDate)){
         $time = $this->secondDiff($daySmenaEnd, $firstDate);
      }
      
      if($this->timeBetween($nightSmenaEnd, $firstDate, $secondDate)){
         $time = $this->secondDiff($nightSmenaEnd, $firstDate);
      }




      return abs($date2 - $date1) - $time;
   }

   
}