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
        return $this->wialon->getTransportsWithZone(41);
    }


    public function excavators()
    {
        return $this->wialon->getExcavators();
    }


    public function geozones()
    {
        return $this->wialon->getGusakGeozones();
    }

    public function account()
    {
        return $this->wialon->getAccount();
    }

    public function getGroups()
    {
        return $this->wialon->getGroups();
    }

    public function getGroupUnitsWithName()
    {
        return $this->wialon->getGroupUnitsWithName((int) env("BASE_GROUP_ALL_DUMPTRUCKS"));
    }

    public function getDumpTrucks()
    {
        return $this->wialon->getDumpTrucks(41);
    }
    
    public function writeToDB()
    {
        return $this->wialon->writeToDB();
    }

    public function getMessagesInterval()
    {
        return $this->wialon->getMessagesInterval(9443, Carbon::parse('2024-11-19 09:00')->timestamp, now()->timestamp);
    }

    

    public function getTransportPoints()
    {
        return $this->wialon->getTransportPoints(10529);
    }
}
