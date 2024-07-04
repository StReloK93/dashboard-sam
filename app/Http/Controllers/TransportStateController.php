<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransportState;
use App\Models\Transport;
use App\Models\TransportList;
use App\Helpers\Smena;
use Carbon\Carbon;
use DB;
use PDF;


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

		return TransportState::with([
			'inSmena' => function ($query) use ($period) {
				$query->whereBetween('geozone_out', [$period['start'], $period['end']]);
			},
			'truck',
			'tracks' => function ($query) {
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

	public function redColumn($transport_id)
	{

		$period = $this->time->getPeriod(now());
		return Transport::select('x', 'y', 'created_at', 'speed')->where([
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

			$filter = array_filter($allStates, function ($oneState) use ($STARTDATES, $ENDDATES, $GEOZONE) {
				if ($GEOZONE == $oneState->geozone && $this->time->timeBetween($oneState->geozone_in, $STARTDATES, $ENDDATES)) {
					return true;
				} else
					return false;
			});




			if (count($filter) > 0) {
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

	public function getParkInformation(Request $request)
	{
		$arr1 = DB::connection('ueb')->select("SELECT * FROM [dbo].UEB_TO_Yed_Okno_AC(?,?, ?)", [$request->startDate, $request->endDate, (int) env("BASE_PARK")]);
		$arr2 = DB::connection('ueb')->select("SELECT * FROM [dbo].UEB_FAKT_TO(?,?, ?)", [$request->startDate, $request->endDate, (int) env("BASE_PARK")]);

		return collect($arr1)->merge($arr2);
	}



	public function exportTablePdf(Request $request)
	{
		// Создаем PDF
		$html = "<html>
		<head>
			<style>
				html{
					width: 100%;
					height: 100%;
					padding: 0;
					margin: 0;
					background: rgb(21 21 26);
				}
				.w-full {
					width: 100%;
				}
				body {
					margin: 0;
					padding: 0;
					background: rgb(21 21 26);
				}
				.leading-3{
					background: rgb(21 21 26);
				}
				.h-9{
					height: 36px;
				}
				.border-x-2.border-zinc-900{
					padding: 0px 5px;
					font-size: 13px;
				}
				.bg-green-900.font-semibold, .bg-red-900.font-semibold, .bg-zinc-900.font-semibold{
					color: white;
					border-radius: 0.125rem;
					margin-right: 0.25rem;
					padding-left: 0.375rem;
    				padding-right: 0.375rem;
					padding-top: 0.12rem;
    				padding-bottom: 0.12rem;
					max-width: 135px;
					display: inline-block;
					align-content: start;
					font-size: 13px;
				}
				.bg-zinc-900{
					background-color: rgb(25 25 28);
				}
				.bg-green-900{
					background-color: rgb(19 76 42);
				}
				.bg-red-900{
					background-color: rgb(126 29 29);
				}
				.bg-zinc-700{
					background-color: rgb(73 73 80);
				}
				.bg-stone-950{
					background: black;
					color: white;
					width: 135px;
					height: 46px;
					text-align: center;
				}

				.bg-zinc-800{
					background: rgb(41 41 44);
				}

			</style>
		</head>
		<body>
			$request->html
		</body>
		</html>";

		$pdf = PDF::loadHTML($html)->setOptions([
			'dpi' => 150, // DPI (dots per inch) настройка для более четкого текста
			'defaultFont' => 'sans-serif', // Шрифт по умолчанию
			'isPhpEnabled' => true // Разрешение выполнения PHP кода в HTML
		])->setPaper('A4', 'landscape')->setOptions([
					'margin-top' => 0,
					'margin-bottom' => 0,
					'margin-left' => 0,
					'margin-right' => 0,
				]);

		// Возвращаем PDF файл для скачивания
		return $pdf->download('exported.pdf');


	}


}