<?php

namespace App\Helpers;

use Carbon\Carbon;

class Smena
{
   public function getPeriod($currentTime)
   {

      $DaySmena = env('BASE_SMENA_DAY');
      $NightSmena = env('BASE_SMENA_NIGHT');

      $current = $currentTime->copy()->startOfDay()->format('Y-m-d');
      $daySmenaStart = Carbon::parse("$current $DaySmena");
      $nightSmenastart = Carbon::parse("$current $NightSmena");

      if ($daySmenaStart <= $currentTime && $currentTime < $nightSmenastart) {
         $smena = 1;
         $start = $daySmenaStart;

         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      } elseif ($currentTime >= $nightSmenastart) {
         $smena = 2;
         $start = $nightSmenastart;

         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      } else {
         $smena = 2;
         $subDay = $currentTime->copy()->subDay()->format('Y-m-d');
         
         $start = Carbon::parse("$subDay $NightSmena");
         $startCopy = $start->copy();
         $end = $startCopy->addHours(12);
      }

      return ['smena' => $smena, 'start' => $start, 'end' => $end];
   }



   public function getSmena($date, $smena)
   {

      $DaySmena = env('BASE_SMENA_DAY');
      $NightSmena = env('BASE_SMENA_NIGHT');

      $current = Carbon::parse($date)->copy()->startOfDay()->format('Y-m-d');

      if ($smena == 1) {
         $start = Carbon::parse("$current $DaySmena");
         $end = $start->copy()->addHour(12);
      } else {
         $start = Carbon::parse("$current $NightSmena");
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

      $DaySmena = env('BASE_SMENA_DAY');
      $DaySmenaJob = env('BASE_SMENA_DAY_JOB');

      $NightSmena = env('BASE_SMENA_NIGHT');
      $NightSmenaJob = env('BASE_SMENA_NIGHT_JOB');


      $date1 = strtotime($firstDate);
      $date2 = strtotime($secondDate);

      $daySmenaStart = date("Y-m-d $DaySmena", $date1);
      $daySmenaEnd = date("Y-m-d $DaySmenaJob", $date1);

      $nightSmenaStart = date("Y-m-d $NightSmena", $date1);
      $nightSmenaEnd = date("Y-m-d $NightSmenaJob", $date1);


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