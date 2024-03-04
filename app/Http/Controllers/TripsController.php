<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class TripsController extends Controller
{
    public function index()
    {
        $period = $this->getPeriod();

        $count = DB::connection('wialon')->table('ReportTrips')
            ->where('idPodrazd', 1)
            ->where('smenaDate', $period['date'])
            ->where('smena', $period['smena'])
            ->count();

        $transports = DB::connection('wialon')->select("SELECT a.*, b.timer, b.distance from
            ( SELECT distinct objectNameFull as name ,objectID as id FROM ReportTrips where idPodrazd=1 and datediff(minute, DatetimeBegin, getdate()) < 31) a
            left join
            ( SELECT count(*) timer, Sum(distance) distance, objectID as id FROM ReportTrips where idPodrazd=1 and smenaDate=? and smena=? group by objectID ) b on a.id = b.id",
            [$period['date'], $period['smena']]
        );

        return [ 'transports' => $transports, 'count' => $count];
    }



    public function getPeriod()
    {
        $currentTime = now();
        $currentHour = $currentTime->format('H');

        if ($currentHour >= 9 && $currentHour < 21) {
            $smena = 1;
            $date = $currentTime->format('Y-m-d');
        } elseif ($currentHour >= 21) {
            $smena = 2;
            $date = $currentTime->format('Y-m-d');
        } else {
            $smena = 2;
            $date = $currentTime->yesterday()->format('Y-m-d');
        }

        return ['smena' => $smena, 'date' => $date];
    }




}
