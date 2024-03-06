<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WialonService;
use App\Models\Transport;
use DB;
class TransportController extends Controller
{

    protected $wialon;
    function __construct(){
        $this->wialon = new WialonService();
    }
    public function index()
    {

        // return TransportState::join(DB::raw('(SELECT transport_id, MAX(geozone_out) AS max_time FROM transport_states GROUP BY transport_id) as latest_transports'), function ($join) {
        //     $join->on('transport_states.transport_id', '=', 'latest_transports.transport_id')
        //         ->on('transport_states.geozone_out', '=', 'latest_transports.max_time');
        // })->select('transport_states.*')->get();
        // // return DB::select("with maxt as(
        // //     SELECT 
        // //         [transport_id]      
        // //         ,max([created_at]) maxTime     
        // //     FROM [dashboard].[dbo].[transports] 
        // //     group by transport_id
        // //     ),
        // //     currientzone as
        // //     (select maxt.*, t.geozone , t.name from maxt ------ qolgan
        // //     left join [dashboard].[dbo].[transports] t on t.transport_id=maxt.transport_id and t.created_at=maxt.maxTime)
            
        // //     ,prevzoneout as
        // //     (SELECT 
        // //           t.[transport_id]      
        // //           , max(t.[created_at]) maxTime     
        // //       FROM [dashboard].[dbo].[transports] t left join currientzone c on t.transport_id=c.transport_id
        // //       where t.created_at<=c.maxTime and isnull(c.geozone,'')<>isnull(t.geozone,'')
        // //       group by t.transport_id)
            
        // //       select c.*,isnull(p.maxTime, (select min(created_at) from [dashboard].[dbo].[transports] t where t.transport_id=c.transport_id )) minTime 
        // //       from currientzone c left join prevzoneout p on c.transport_id=p.transport_id");
        
    }


    public function getWithWialon(){
        return $this->wialon->getTransportsWithZone();
    }


    public function excavators(){
        return $this->wialon->getExcavators();
    }
}