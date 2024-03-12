<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransportState;
use App\Helpers\Smena;
use DB;
use App\Services\WialonService;
class TransportStateController extends Controller
{
    public function index()
    {
        $wialon = new WialonService();
        $collection = collect($wialon->getTransportPoints(7381))->pluck('transport_id');
        

        return TransportState::with(['truck','currentDay'])
        ->whereIn('transport_states.transport_id', $collection)
        ->join(DB::raw('(SELECT transport_id, MAX(geozone_out) AS max_time FROM transport_states GROUP BY transport_id) as latest_transports'), 
            function ($join) {
                $join->on('transport_states.transport_id', '=', 'latest_transports.transport_id')
                ->on('transport_states.geozone_out', '=', 'latest_transports.max_time');
            }
        )
        ->select('transport_states.*')->get();
    }


    public function show($transportId){
        $smenaClass = new Smena();
        $period = $smenaClass->getPeriod();
        return TransportState::where('transport_id', $transportId)
        ->where('geozone_out', '>',  $period['start'])
        ->where('geozone_out', '<',  $period['end'])
        ->get();
    }


}
