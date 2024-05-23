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
    public function __construct(){
        $this->time = new Smena();
    }

    public function getSpeedsByHour(Request $request){

        if($request->startDate == null){
            // $time = $this->time->getPeriod(now());
            $startDate = now()->copy()->addHour(-24);
            $endDate = now();
        }
        else{
            $startDate = Carbon::parse($request->startDate)->timezone('Asia/Tashkent');
            $endDate = Carbon::parse($request->endDate)->timezone('Asia/Tashkent');
        }

        return Point::select(
            DB::raw('DATEADD(HOUR, DATEDIFF(HOUR, 0, T), 0) AS hour'),
            DB::raw('ROUND(AVG(CASE WHEN Speed <> 0 THEN CAST(Speed AS DECIMAL(10, 2)) ELSE NULL END), 2) AS average_speed'),
        )
        ->whereBetween('T', [$startDate, $endDate])
        ->groupBy(DB::raw('DATEADD(HOUR, DATEDIFF(HOUR, 0, T), 0)'))
        ->orderBy('hour')
        ->get();
    }






    public function excavatorState()
    {
        return DB::connection('oracle')
            ->select("SELECT a.tech_id,
            a.tech_type,
            a.garage,
            s.status,
            s.status_time,
            (CASE WHEN EXTRACT (DAY FROM (SYSTIMESTAMP - s.status_time)) > 0 THEN EXTRACT (DAY FROM (SYSTIMESTAMP - s.status_time)) || ':' END
             || SUBSTR (SYSTIMESTAMP - s.status_time, 12, 5)) status_passed_time, --Shu statusga tushganiga qancha vaqt bo'lganligi(Kun:Soat:Minut formatida)
            a.tech_group_name,
            a.tech_name,
            a.div_id,
            a.div_name,
            a.place_id,
            a.place_name,
            a.tech_type_id,
            a.tech_type_aggregate,
            a.tech_group_id,
            a.wialon_id,
            (select max(msg_dt)msg_dt from edt.t_edt_messages m where m.tech_id=a.tech_id and m.tech_type=a.tech_type) msg_dt
            FROM v_edt_techs a
            LEFT JOIN edt.t_edt_laststatus s
            ON a.tech_id = s.tech_id AND a.tech_type = s.tech_type
            WHERE a.place_id IN (113) AND a.tech_type = 'ex'");

    }


    public function carReports(Request $request)
    {

        $startDate = Carbon::parse($request->date)
            ->timezone('Asia/Tashkent')
            ->startOfDay()
            ->addHours(9)
            ->addMinutes(10);


        return DB::select("SELECT * FROM [dbo].[FMTBF_MTTR] (?, ?, 7)
        order by [name] ,[hafta]", [$startDate, $request->weekCount]);

    }


    public function carReportsQuarter(Request $request)
    {

        $startDate = Carbon::parse($request->date)
            ->timezone('Asia/Tashkent')
            ->startOfDay()
            ->addHours(9)
            ->addMinutes(10);


        return DB::select("SELECT * FROM [dbo].[FMTBF_MTTR] (?, 12, 7)
        order by [name], [hafta]", [$startDate]);

    }



}
