<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransportState;
use App\Models\TransportList;
use App\Helpers\Smena;
use DB;
use App\Services\WialonService;
class TransportStateController extends Controller
{
    public function index()
    {

        $list = TransportList::latest('id')->first();

        return TransportState::with(['truck','currentDay', 'tracks'])
        ->whereIn('transport_states.transport_id', $list->tranports)
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


    public function excavator(){
        return TransportState::where('geozone', 'ШКБ ЭКГ-46')->get();
    }


    public function update($id,Request  $req){
        $state = TransportState::find($id);
        $state->cause = $req->cause;
        $state->save();
    }


}
