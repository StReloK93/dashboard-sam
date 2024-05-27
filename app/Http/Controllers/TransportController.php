<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\WialonService;
class TransportController extends Controller
{
    protected $wialon;
    function __construct()
    {
        $this->wialon = new WialonService();
    }

    public function getWithWialon()
    {
        return $this->wialon->getTransportsWithZone();
    }


    public function excavators()
    {
        return $this->wialon->getExcavators();
    }


    public function geozones()
    {
        return $this->wialon->getGusakGeozones();
    }


}