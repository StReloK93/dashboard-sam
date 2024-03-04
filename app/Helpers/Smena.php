<?php

namespace App\Helpers;

use Carbon\Carbon;

class Smena
{
   public function getPeriod()
   {
      $currentTime = now();
      $hour = $currentTime->format('H');
      $startOfDay = $currentTime->startOfDay();

      if ($hour >= 9 && $hour < 21) {
         $smena = 1;
         $start = $startOfDay->addHours(9);
         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      } elseif ($hour >= 21) {
         $smena = 2;
         $start = $startOfDay->addHours(21);
         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      } else {
         $smena = 2;
         $start = $startOfDay->subDay()->addHours(21);
         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      }

      return ['smena' => $smena, 'start' => $start, 'end' => $end];
   }
}