<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransportState;
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



		$transport =  TransportState::with([
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

			dd($transport);

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

		$startDate = Carbon::parse($request->startDate)
		->timezone('Asia/Tashkent')
		->startOfDay()
		->addHours(9)
		->addMinutes(10);

		

		$endDate = Carbon::parse($request->endDate)
		->timezone('Asia/Tashkent')
		->startOfDay()
		->addHours(9)
		->addMinutes(10);

		$key = 'Заправочный';

		$allStates = DB::select("SELECT A.* ,B.smenaDate,B.smena,B.teamNum FROM transport_states A
  		left join WIALON.dbo.ReportSmenaTeam B ON 
   	(case when cast(geozone_in as time) between '09:10' AND '21:10' then 1 else 2 end) = B.smena
  			AND (case when cast(geozone_in as time) between '09:10' AND '21:10' then cast(geozone_in as date)
			else case when cast(geozone_in as time) between '21:10:01' AND '23:59:59' then cast(geozone_in as date)
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
					// $difference2 = $also ? $this->time->secondDiff($car->geozone_in, $car->geozone_out) : $this->time->secondDiff($car->geozone_in, $ENDDATES);


					$arr[] = [
						'difference' => $difference,
						// 'difference2' => $difference2,
						'smena' => $state->teamNum,
						'smenaDate' => $state->smenaDate,
					];
				}

			}

		}

		return $arr;
	}

}
