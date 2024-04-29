<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransportState;
use App\Models\TransportList;
use App\Helpers\Smena;
use DB;


class TransportStateController extends Controller
{
	public function index()
	{
		$list = TransportList::latest('id')->first();
		$smenaClass = new Smena();
		$period = $smenaClass->getPeriod(now());



	// 	$states = TransportState::with([
	// 		'inSmena' => function ($query) use ($period) {
	// 			 $query->where('geozone_out', '>', $period['start'])
	// 					 ->where('geozone_out', '<', $period['end']);
	// 		},
	// 		'truck',
	// 		'tracks'
	//   ])
	//   ->select('transport_id', 'name') // Here, specify the table name prefix for 'transport_id'
	//   ->whereIn('transport_id', $list->tranports) // Use the table name prefix for 'transport_id' here as well
	//   ->groupBy('transport_id', 'name') // Use the table name prefix for 'transport_id' here as well
	//   ->get();

	// 	return $states;
	// 	dd($states);




	

		return TransportState::with([
			'inSmena' => function ($query) use ($period) {
				$query->where('geozone_out', '>', $period['start'])
					->where('geozone_out', '<', $period['end']);
			},
			// 'truck',
			'tracks' => function ($query) use ($period) {
				$query->where('created_at', '>=', now()->subMinutes(10));
				// $query->where('created_at', '>', $period['start'])
				// 	->where('created_at', '<', $period['end']);
			},
		])
			->whereIn('transport_states.transport_id', $list->tranports)
			->join(
				DB::raw('(SELECT transport_id, MAX(geozone_out) AS max_time FROM transport_states GROUP BY transport_id) as latest_transports'),
				function ($join) {
					$join->on('transport_states.transport_id', '=', 'latest_transports.transport_id')
						->on('transport_states.geozone_out', '=', 'latest_transports.max_time');
				}
			)
			->select('transport_states.*')->get();
	}


	public function show($transportId)
	{
		$smenaClass = new Smena();
		$period = $smenaClass->getPeriod(now());
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


}
