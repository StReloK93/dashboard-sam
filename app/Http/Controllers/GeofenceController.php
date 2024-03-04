<?php

namespace App\Http\Controllers;

use Http;
use Illuminate\Http\Request;
use App\Services\WialonService;
class GeofenceController extends Controller
{
    protected $wialon;
    function __construct(WialonService $wialon)
    {
        $this->wialon = $wialon;
    }
    public function index()
    {
        return $this->wialon->getGeozones();
    }

}
