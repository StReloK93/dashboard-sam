<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TransportState;
use App\Models\TransportList;
use App\Models\Causes;
use App\Helpers\Smena;
use Carbon\Carbon;
use DB;


class TransportStateController extends Controller
{
	private $time;
	public function __construct()
	{
		$this->time = new Smena();
	}

	public function index()
	{

		$list = TransportList::latest('id')->first();
		$period = $this->time->getPeriod(now());
		$ehtimol = $this->lastDayRepuclic($period);


		$query = TransportState::with([
			'inSmena' => function ($query) use ($period) {
				$query->whereBetween('geozone_out', [$period['start'], $period['end']]);
			},
			'truck',
			'causes',
			'tracks' => function ($query) {
				$query->where('created_at', '>=', now()->subMinutes(10));
			},
		]);
		if (env('BASE_SMENA_DAY' != "07:50")) {
			$query->whereNot('name', 'like', '%MAN%');
		}

		$transports = $query->whereIn('transport_states.transport_id', $list->tranports)
			->join(
				DB::raw('(SELECT transport_id, MAX(id) AS max_id FROM transport_states GROUP BY transport_id) as latest_transports'),
				function ($join) {
					$join->on('transport_states.transport_id', '=', 'latest_transports.transport_id')
						->on('transport_states.id', '=', 'latest_transports.max_id');
				}
			)
			->select(
				'transport_states.id',
				'transport_states.transport_id',
				'transport_states.name',
				'transport_states.geozone_in',
				'transport_states.geozone_out',
				'transport_states.geozone'
			)
			->get();

		foreach ($transports as $key => $state) {
			$selected = collect($ehtimol)->where('transport_id', $state->transport_id)->first();
			$transports[$key]->reyslar = $selected ? $selected->QolganReyslar : "0";
		}
		return $transports;
	}

	public function selectSmena(Request $request)
	{

		if ($request->date) {
			$period = $this->time->getSmena($request->date, $request->smena);
		} else {
			$period = $this->time->getPeriod(now());
		}

		$state = TransportState::with('causes')->whereBetween('geozone_out', [$period['start'], $period['end']])->get();

		return ['smena' => $period, 'states' => $state];
	}


	public function show($transportId)
	{
		$period = $this->time->getPeriod(now());
		return TransportState::with('causes')->where('transport_id', $transportId)
			->where('geozone_out', '>', $period['start'])
			->where('geozone_out', '<', $period['end'])
			->get();
	}

	public function update($id, Request $req)
	{
		Causes::where('transport_state_id', $id)->whereNotIn('cause_id', $req->causes)->delete();


		$remotes = Causes::where('transport_state_id', $id)
			->whereIn('cause_id', $req->causes)
			->get()
			->pluck('cause_id')
			->all();

		foreach ($req->causes as $key => $cause_id) {
			$isset = in_array($cause_id, $remotes);
			if ($isset == false) {
				Causes::create([
					'transport_state_id' => $id,
					'cause_id' => $cause_id,
				]);
			}
		}


		return Causes::where('transport_state_id', $id)->get();
	}





	public function waitingInOilGraphic(Request $request)
	{
		$dayStart = env('BASE_SMENA_DAY');
		$nightStart = env('BASE_SMENA_NIGHT');
		$start = Carbon::parse($request->startDate)->startOfDay()->format('Y-m-d');
		$end = Carbon::parse($request->endDate)->startOfDay()->format('Y-m-d');

		$startDate = Carbon::parse("$start $dayStart");
		$endDate = Carbon::parse("$end $dayStart");


		$allStates = DB::select("SELECT A.* ,B.smenaDate,B.smena,B.teamNum FROM transport_states A 
			left join WIALON.dbo.ReportSmenaTeam B ON 
			(case when cast(geozone_in as time) between '$dayStart' AND '$nightStart' then 1 else 2 end) = B.smena
				AND (case when cast(geozone_in as time) between '$dayStart' AND '$nightStart' then cast(geozone_in as date)
				else case when cast(geozone_in as time) between '$nightStart' AND '23:59:59' then cast(geozone_in as date)
				else dateadd(day, -1, cast(geozone_in as date))  end end) = B.smenaDate and idPodrazd = ?
				WHERE A.geozone_in between ? AND ? AND a.geozone LIKE N'%' + ? + '%'
				AND DATEDIFF(SECOND, A.geozone_in, A.geozone_out) > 59
			order by smenaDate", [(int) env("BASE_PARK"), $startDate, $endDate, 'Заправочный']);


		return response($allStates)
			->header('0dayStart', $dayStart)
			->header('0nightStart', $nightStart)
			->header('0startDate', $startDate)
			->header('0endDate', $endDate);
	}





	public function getParkInformation(Request $request)
	{
		$arr1 = DB::connection('ueb')->select("SELECT * FROM [dbo].UEB_TO_Yed_Okno_AC(?,?, ?)", [$request->startDate, $request->endDate, (int) env("BASE_PARK")]);
		$arr2 = DB::connection('ueb')->select("SELECT * FROM [dbo].UEB_FAKT_TO(?,?, ?)", [$request->startDate, $request->endDate, (int) env("BASE_PARK")]);

		return collect($arr1)->merge($arr2);
	}


	private function lastDayRepuclic($period)
	{
		$start = $period['start'];
		$end = $period['end'];
		try {
			return DB::select("SELECT transport_id
		,Davomiyligi 
		,datediff(second, getdate(), ?)/DavomiyligiUrtacha QolganReyslar
		FROM
		(SELECT *
		,sum(Davomiyligi) over (partition by transport_id order by geozone_in) RS
		,avg(Davomiyligi) over (partition by transport_id order by geozone_in) DavomiyligiUrtacha
		FROM
		(SELECT [id]
				,[transport_id]
				,[geozone]
				,[geozone_type]
				,[geozone_id]
				,[geozone_in]
				,[geozone_out]
				,[distance]
				,[counter]
				,[last_message_time]
			, geozone_in ReysBoshlanishi
			,lead(geozone_in) over (partition by transport_id order by geozone_in) ReysTugashi
			,row_number() over (partition by transport_id order by geozone_in desc) RN
			,datediff(second, geozone_in, lead(geozone_in) over (partition by transport_id order by geozone_in)) Davomiyligi	  
		FROM [WialonAPI].[dbo].[truck_states]
		WHERE --transport_id=6350  and 
		geozone_in>= ? and geozone_out<= ?
		and geozone_type in (2)
		) R
		WHERE RN<=6-- and ReysTugashi is not null
		)R1
		WHERE RN<=2 and ReysTugashi is not null
 	 	order by geozone_in desc",
				[$end, $start, $end]
			);
		} catch (\Throwable $th) {
			return [];
		}

	}
}