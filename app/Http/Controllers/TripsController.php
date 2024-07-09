<?php

namespace App\Http\Controllers;

use App\Helpers\Smena;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Point;
use DB;

class TripsController extends Controller
{
    protected $time;
    public function __construct()
    {
        $this->time = new Smena();
    }

    public function getSpeedsByHour(Request $request)
    {

        if ($request->startDate == null) {
            // $time = $this->time->getPeriod(now());
            $startDate = now()->copy()->addHour(-24);
            $endDate = now();
        } else {
            $startDate = Carbon::parse($request->startDate)->timezone('Asia/Tashkent');
            $endDate = Carbon::parse($request->endDate)->timezone('Asia/Tashkent');
        }

        return Point::select(
            DB::raw('DATEADD(HOUR, DATEDIFF(HOUR, 0, time_message), 0) AS hour'),
            DB::raw('ROUND(AVG(CASE WHEN Speed <> 0 THEN CAST(Speed AS DECIMAL(10, 2)) ELSE NULL END), 2) AS average_speed'),
        )
            ->whereBetween('time_message', [$startDate, $endDate])
            ->groupBy(DB::raw('DATEADD(HOUR, DATEDIFF(HOUR, 0, time_message), 0)'))
            ->orderBy('hour')
            ->get();
    }






    public function excavatorState()
    {
        return DB::select("select * from wialon.dbo.ex_status(?, 1, ?)",[now()->format('Y-m-d'), env("BASE_PARK")]);
    }


    public function carReports(Request $request)
    {
        $DaySmena = env('BASE_SMENA_DAY');

        $current = Carbon::parse($request->date)->startOfDay()->format('Y-m-d');
        $startDate = Carbon::parse("$current $DaySmena");
        return DB::select("SELECT * FROM [dbo].[FMTBF_MTTR] (?, ?, 7)
        order by [name] ,[hafta]", [$startDate, $request->weekCount]);

    }


    public function carReportsQuarter(Request $request)
    {
        $DaySmena = env('BASE_SMENA_DAY');

        $current = Carbon::parse($request->date)->startOfDay()->format('Y-m-d');
        $startDate = Carbon::parse("$current $DaySmena");


        return DB::select("SELECT * FROM [dbo].[FMTBF_MTTR] (?, 12, 7)
        order by [name], [hafta]", [$startDate]);

    }



    public function getCauseList()
    {
        return DB::connection('wialon')->select("
            SELECT Detail.id , Detail.name , Main.name as main  FROM SprPrichinaOstanovkiDetail Detail INNER JOIN SprPrichinaOstanovkiMain Main ON Detail.idMain = Main.id
        ");
    }


}
