<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransportState;
use App\Models\Transport;
use App\Models\TransportList;
use App\Helpers\Smena;
use Carbon\Carbon;
use DB;


class TransportStateController extends Controller
{
	private $time;
	public function __construct(){
		$this->time = new Smena();
	}

	public function index()
	{
		$list = TransportList::latest('id')->first();
		$period = $this->time->getPeriod(now());
		
		return TransportState::with([
			'inSmena' => function ($query) use ($period) {
				$query->whereBetween('geozone_out', [$period['start'], $period['end']]);
			},
			'truck',
			'tracks' => function ($query){
				$query->where('created_at', '>=', now()->subMinutes(10));
			},
		])
			->whereNot('name', 'like', '%MAN%')
			->whereIn('transport_states.transport_id', $list->tranports)
			->join(
				DB::raw('(SELECT transport_id, MAX(id) AS max_id FROM transport_states GROUP BY transport_id) as latest_transports'),
				function ($join) {
					$join->on('transport_states.transport_id', '=', 'latest_transports.transport_id')
						->on('transport_states.id', '=', 'latest_transports.max_id');
				}
			)
			->select('transport_states.*')->get();

	}

	public function redColumn($transport_id){

		$period = $this->time->getPeriod(now());
		return Transport::select('x', 'y' , 'created_at' ,'speed')->where([
				['transport_id', $transport_id],
				['geozone', null],
			])
			->whereBetween('created_at', [$period['start'], $period['end']])
			->get();
	}

	public function selectSmena(Request $request)
	{

		if ($request->date) {
			$period = $this->time->getSmena($request->date, $request->smena);
		} else {
			$period = $this->time->getPeriod(now());
		}

		$state = TransportState::whereBetween('geozone_out', [$period['start'], $period['end']])->get();

		return ['smena' => $period, 'states' => $state];
	}


	public function show($transportId)
	{
		$period = $this->time->getPeriod(now());
		return TransportState::where('transport_id', $transportId)
			->where('geozone_out', '>', $period['start'])
			->where('geozone_out', '<', $period['end'])
			->get();
	}


	public function excavator()
	{
		return TransportState::where('geozone', 'ШКБ ЭКГ-46')->get();
	}


	public function update($id, Request $req)
	{
		$state = TransportState::find($id);
		$state->cause = $req->cause;
		$state->save();
	}


	public function waitingInOilGraphic(Request $request)
	{
		$dayStart = env('BASE_SMENA_DAY');
		$nightStart = env('BASE_SMENA_NIGHT');

		$start = Carbon::parse($request->startDate)->startOfDay()->format('Y-m-d');
		$end = Carbon::parse($request->endDate)->startOfDay()->format('Y-m-d');

		$startDate = Carbon::parse("$start $dayStart");


		
		$endDate = Carbon::parse("$end $dayStart");

		$key = 'Заправочный';



		$allStates = DB::select("SELECT A.* ,B.smenaDate,B.smena,B.teamNum FROM transport_states A
  		left join WIALON.dbo.ReportSmenaTeam B ON 
   	(case when cast(geozone_in as time) between '$dayStart' AND '$nightStart' then 1 else 2 end) = B.smena
  			AND (case when cast(geozone_in as time) between '$dayStart' AND '$nightStart' then cast(geozone_in as date)
			else case when cast(geozone_in as time) between '$nightStart' AND '23:59:59' then cast(geozone_in as date)
			else dateadd(day, -1, cast(geozone_in as date))  end end) = B.smenaDate 
			WHERE A.geozone_in between ? AND ? AND a.geozone LIKE N'%' + ? + '%' AND smena = 1
			AND DATEDIFF(SECOND, A.geozone_in, A.geozone_out) > 59
		", [$startDate, $endDate, $key]);
		$arr = [];


		foreach ($allStates as $state) {

			$STARTDATES = $state->geozone_in;
			$ENDDATES = $state->geozone_out;
			$GEOZONE = $state->geozone;

			$filter = array_filter($allStates, function ($oneState) use($STARTDATES, $ENDDATES, $GEOZONE) {
				if($GEOZONE == $oneState->geozone && $this->time->timeBetween($oneState->geozone_in, $STARTDATES, $ENDDATES)){
					return true;
				} else
					return false;
			});




			if (count($filter) > 0){
				foreach ($filter as $car) {
					$also = $this->time->timeBetween($car->geozone_out, $STARTDATES, $ENDDATES);

					$difference = $also ? $this->time->secondDiffWithoutSmena($car->geozone_in, $car->geozone_out) : $this->time->secondDiffWithoutSmena($car->geozone_in, $ENDDATES);

					$arr[] = [
						'difference' => $difference,
						'smena' => $state->teamNum,
						'smenaDate' => $state->smenaDate,
					];
				}

			}

		}

		return $arr;
	}

	public function getParkInformation(Request $request){
		$arr1 = DB::connection('ueb')->select("SELECT * FROM [dbo].UEB_TO_Yed_Okno_AC(?,?, ?)", [$request->startDate, $request->endDate, (int) env("BASE_PARK")]);
		$arr2 = DB::connection('ueb')->select("SELECT * FROM [dbo].UEB_FAKT_TO(?,?, ?)", [$request->startDate, $request->endDate, (int) env("BASE_PARK")]);

		return  collect($arr1)->merge($arr2);
	}
}