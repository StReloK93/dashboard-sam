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
}