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
        
    }


    public function getWithWialon(){
        return $this->wialon->getTransportsWithZone();
    }


    public function excavators(){
        return $this->wialon->getExcavators();
    }
}