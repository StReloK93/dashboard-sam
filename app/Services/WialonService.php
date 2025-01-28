<?php
namespace App\Services;

use App\Services\GeoZoneService;
use App\Models\TransportList;
use App\Models\TransportState;
use App\Services\Muruntau;
use App\Wialon\WialonApi;
use DB;
class WialonService
{
    protected $wialonApi, $geoService;

    function __construct()
    {
        $this->wialonApi = new WialonApi();
        $this->geoService = new GeoZoneService();
    }

    public function dumpTrucksPosition($radius)
    {
        $dumpTrucks = $this->wialonApi->groupTransportWithLastMessage((int) env('BASE_GROUP_ALL_DUMPTRUCKS'));
        $mainZones = $this->wialonApi->getGeozonesGroup((int) env('BASE_GEOZONES_GROUP_ID'));

        $excavators = collect($this->wialonApi->groupTransportWithLastMessage((int) env('BASE_GROUP_FRONTAL')))
            ->merge($this->wialonApi->groupTransportWithLastMessage((int) env('BASE_GROUP_EXCAVATOR')))
            ->merge($this->customZones())
            ->all();

        foreach ($dumpTrucks as $key => $car) {
            $pointCar = ['x' => $car['x'], 'y' => $car['y']];
            $geozoneName = $this->geoService->findZone($pointCar, $mainZones);

            if ($geozoneName) {
                $dumpTrucks[$key]['distance_ex'] = null;
                $dumpTrucks[$key]['geozone'] = $geozoneName;
                continue;
            }

            $distances = $this->geoService->getDistances($pointCar, $excavators);
            $closestDistance = $distances[0]['distance'] ?? PHP_INT_MAX;

            $dumpTrucks[$key]['distance_ex'] = ($closestDistance < $radius) ? round($closestDistance) : null;
            $dumpTrucks[$key]['geozone'] = ($closestDistance < $radius) ? $distances[0]['name'] : null;
        }

        return collect($dumpTrucks)->merge($this->forOnlyPistali($mainZones))->all();
    }


    public function waterTrucksPosition()
    {
        $waterTrucks = $this->wialonApi->groupTransportWithLastMessage((int) env('BASE_GROUP_ALL_WATERTRUCKS'));
        $gusakZones = $this->wialonApi->getGeozonesGroup((int) env('BASE_GUSAKS_GROUP_ID'));

        foreach ($waterTrucks as $key => $car) {
            $pointCar = ['x' => $car['x'], 'y' => $car['y']];

            $geozoneName = $this->geoService->findZone($pointCar, $gusakZones);
            $waterTrucks[$key]['distance_ex'] = null;
            $waterTrucks[$key]['geozone'] = $geozoneName == null ? null : $geozoneName;
        }

        return $waterTrucks;
    }


    private function truckLastPositions(){
        $transportList = TransportList::latest('id')->first();
        return TransportState::whereIn('transport_states.transport_id', $transportList->tranports)
        ->join(
            DB::raw('(SELECT transport_id, MAX(id) AS max_id FROM transport_states GROUP BY transport_id) as latest_transports'),
            function ($join) {
                $join->on('transport_states.transport_id', '=', 'latest_transports.transport_id')
                    ->on('transport_states.id', '=', 'latest_transports.max_id');
            }
        )
        ->select('transport_states.*')->get();
    }


    public function writeToDB()
    {
        $transports = collect($this->dumpTrucksPosition(41))->merge($this->waterTrucksPosition())->all();
        $time = now();
        $lastPositions = $this->truckLastPositions();

        $newStates = [];
        foreach ($transports as $car) {
            $transport = $lastPositions->where('transport_id', $car['transport_id'])->first();

            if ($transport?->geozone == $car['geozone']) {
                $transport->geozone_out = $time->format('Y-m-d H:i:s');
                $transport->save();
            } else {
                $newStates[] = [
                    'name' => $car['name'],
                    'transport_id' => $car['transport_id'],
                    'geozone' => $car['geozone'],
                    'geozone_in' => $time->format('Y-m-d H:i:s'),
                    'geozone_out' => $time->format('Y-m-d H:i:s'),
                ];
            }
        }
        
        $chunks = array_chunk($newStates, 10);
        foreach ($chunks as $chunk) {
            TransportState::insert($chunk);
        }
        
        $this->muruntauBase();
        $this->reSaveTransports($transports);
    }

























    private function muruntauBase()
    {
        $service = new Muruntau();
        $service->writeDb($this->dumpTrucksPosition(75), 'ReportStaysMekhanizmsLastCordinate');
        $service->writeDb($this->dumpTrucksPosition(100), 'ReportStaysMekhanizmsLastCordinate50');
        $service->writeDb($this->dumpTrucksPosition(150), 'ReportStaysMekhanizmsLastCordinate75');
    }


    private function reSaveTransports($transports)
    {
        $transportList = TransportList::latest('id')->first();
        $collection = collect($transports)->pluck('transport_id')->all();
        if (isset($transportList)) {
            $transportList->tranports = $collection;
            $transportList->save();
        } else {
            TransportList::create(['tranports' => $collection]);
        }
        DB::table('transports')->insert($transports);
    }


    private function forOnlyPistali($zones)
    {
        $pistali_frontals_id = (int) env('PISTALI_FRONTALS');
        $pistali_mans_id = (int) env('PISTALI_MANS');

        if ($pistali_frontals_id && $pistali_mans_id) {
            $mans = $this->wialonApi->groupTransportWithLastMessage($pistali_mans_id);
            $frontals = $this->wialonApi->groupTransportWithLastMessage($pistali_frontals_id);


            foreach ($mans as $key => $car) {
                $pointCar = ['x' => $car['x'], 'y' => $car['y']];

                $geozoneName = $this->geoService->findZone($pointCar, $zones);

                if ($geozoneName == null) {
                    $distances = $this->geoService->getDistances($pointCar, $frontals);

                    if ($distances[0]['distance'] < 41) {
                        $mans[$key]['distance_ex'] = round($distances[0]['distance']);
                        $mans[$key]['geozone'] = $distances[0]['name'];
                    } else {
                        $mans[$key]['distance_ex'] = null;
                        $mans[$key]['geozone'] = null;
                    }

                } else {
                    $mans[$key]['distance_ex'] = null;
                    $mans[$key]['geozone'] = $geozoneName;
                }
            }

        } else {
            $mans = [];
        }

        return $mans;
    }

    private function customZones()
    {
        if (empty(env('DB_DATABASE_ONLINE')))
            return collect([]);

        return collect([
            [
                'speed' => 0,
                'y' => 41.503154,
                'x' => 64.587814,
                'time_message' => now()->format('Y-m-d H:i:s'),
                'name' => 'РМ Экскаватор C | КННК (руда)',
                'transport_id' => 1,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'speed' => 0,
                'y' => 41.507430,
                'x' => 64.562078,
                'time_message' => now()->format('Y-m-d H:i:s'),
                'name' => 'РМ Экскаватор А | ЦПТ «Северный» (вскрыша)',
                'transport_id' => 2,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'speed' => 0,
                'y' => 41.481487,
                'x' => 64.584909,
                'time_message' => now()->format('Y-m-d H:i:s'),
                'name' => 'РМ Экскаватор В1 |ЦПТ «Южный» (вскрыша)',
                'transport_id' => 3,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'speed' => 0,
                'y' => 41.478547,
                'x' => 64.584184,
                'time_message' => now()->format('Y-m-d H:i:s'),
                'name' => 'РМ Экскаватор В2 |ЦПТ «Южный» (вскрыша)',
                'transport_id' => 4,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}