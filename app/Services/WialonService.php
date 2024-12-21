<?php

namespace App\Services;

use App\Wialon\AuthWialon;

use DB;
use Carbon\Carbon;
use App\Models\TransportState;
use App\Models\TransportList;
use App\Services\GeoZoneService;
use App\Services\Muruntau;
class WialonService
{
    protected $wialon, $frontal_id, $excavator_id, $dumptrucks_id, $account_index, $account, $geozones_group_id, $gusaks_group_id, $watertrucks_id;

    protected $geoService;
    function __construct()
    {
        $this->frontal_id = (int) env('BASE_GROUP_FRONTAL');
        $this->excavator_id = (int) env('BASE_GROUP_EXCAVATOR');
        $this->dumptrucks_id = (int) env('BASE_GROUP_ALL_DUMPTRUCKS');
        $this->account_index = (int) env('BASE_ACCOUNT_INDEX');
        $this->geozones_group_id = (int) env('BASE_GEOZONES_GROUP_ID');
        $this->account = env('BASE_ACCOUNT');

        $this->watertrucks_id = (int) env('BASE_GROUP_ALL_WATERTRUCKS');
        $this->gusaks_group_id = (int) env('BASE_GUSAKS_GROUP_ID');

        $this->wialon = AuthWialon::getInstance();

        $this->geoService = new GeoZoneService();
    }



    public function getDumpTrucks($radius)
    {
        $dumpTrucks = $this->getTransportPoints($this->dumptrucks_id);
        $zones = $this->getMainGeozones();
        $excavators = $this->getExcavators();

        foreach ($dumpTrucks as $key => $car) {
            $pointCar = ['x' => $car['x'], 'y' => $car['y']];

            $geozoneName = $this->geoService->findZone($pointCar, $zones);

            if ($geozoneName == null) {
                $distances = $this->geoService->getDistances($pointCar, $excavators);

                if ($distances[0]['distance'] < $radius) {
                    $dumpTrucks[$key]['distance_ex'] = round($distances[0]['distance']);
                    $dumpTrucks[$key]['geozone'] = $distances[0]['name'];
                } else {
                    $dumpTrucks[$key]['distance_ex'] = null;
                    $dumpTrucks[$key]['geozone'] = null;
                }

            } else {
                $dumpTrucks[$key]['distance_ex'] = null;
                $dumpTrucks[$key]['geozone'] = $geozoneName;
            }
        }


        // for only pistali
        $pistali_frontals_id = (int) env('PISTALI_FRONTALS');
        $pistali_mans_id = (int) env('PISTALI_MANS');

        if ($pistali_frontals_id && $pistali_mans_id) {
            $mans = $this->getTransportPoints($pistali_mans_id);
            $frontals = $this->getTransportPoints($pistali_frontals_id);


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

        // for only pistali


        return collect($dumpTrucks)->merge($mans)->toArray();
    }


    public function getWaterTrucks()
    {

        $waterTrucks = $this->getTransportPoints($this->watertrucks_id);
        $gusakZones = $this->getGusakGeozones();


        foreach ($waterTrucks as $key => $car) {
            $pointCar = ['x' => $car['x'], 'y' => $car['y']];

            $geozoneName = $this->geoService->findZone($pointCar, $gusakZones);
            $waterTrucks[$key]['distance_ex'] = null;
            $waterTrucks[$key]['geozone'] = $geozoneName == null ? null : $geozoneName;
        }

        return $waterTrucks;
    }

    public function getTransportsWithZone($radius)
    {
        $waterTrucksDisabled = $this->watertrucks_id == 0 || $this->gusaks_group_id == 0;

        $waterTrucks = $waterTrucksDisabled ? null : $this->getWaterTrucks();
        $dumpTrucks = $this->getDumpTrucks($radius);

        return collect($dumpTrucks)->merge($waterTrucks)->toArray();
    }




    public function getExcavators()
    {

        $customZones = $this->customZones();

        $frontal = $this->frontal_id ? $this->getTransportPoints($this->frontal_id) : null;

        $excavator = $this->getTransportPoints($this->excavator_id);
        $collection = collect($frontal)->merge($excavator)->merge($customZones);

        return $collection->all();
    }

    public function getTransportPoints($groupIndex)
    {
        if ($groupIndex == null || $groupIndex == 0)
            return [];
        $created = now()->format('Y-m-d H:i:s');
        $groupUnits = $this->getGroupUnitsWithName($groupIndex);
        $transports = [];

        foreach ($groupUnits as $key => $value) {
            $point = $this->wialon->get([
                'svc' => 'messages/load_last',
                'params' => json_encode([
                    'itemId' => $value['id'],
                    'lastTime' => now()->timestamp,
                    'lastCount' => 1,
                    'flags' => 1,
                    'flagsMask' => 65281,
                    'loadCount' => 1,
                ]),
            ]);

            if (count($point['messages']) != 0) {
                $transports[$key]['speed'] = $point['messages'][0]['pos']['s'];
                $transports[$key]['y'] = $point['messages'][0]['pos']['y'];
                $transports[$key]['x'] = $point['messages'][0]['pos']['x'];
                $transports[$key]['time_message'] = Carbon::createFromTimestamp($point['messages'][0]['t'])->toDateTimeString();
                $transports[$key]['name'] = $value['nm'];
                $transports[$key]['transport_id'] = $value['id'];
                $transports[$key]['created_at'] = $created;
            }
        }

        return $transports;
    }


    public function getMessagesInterval($transport_id, $from, $to)
    {
        return $this->wialon->get([
            'svc' => 'messages/load_interval',
            'params' => json_encode([
                'itemId' => $transport_id,
                'timeFrom' => $from,
                'timeTo' => $to,
                'flags' => 1,
                "flagsMask" => 65281,
                "loadCount" => 100
            ]),
        ]);
    }

    public function getAccount()
    {
        $accountsInformation = $this->wialon->get([
            'svc' => 'core/search_items',
            'params' => json_encode([
                'spec' => [
                    'itemsType' => 'avl_resource',
                    'propName' => 'sys_name',
                    'propValueMask' => '*',
                    'sortType' => 'sys_name',
                ],
                'force' => 1,
                'flags' => 1048577,
                'from' => 0,
                'to' => 0,
            ]),
        ]);

        $accounts = $accountsInformation['items'];
        return $this->array_find($accounts, fn($account) => $account['nm'] == $this->account);
    }


    public function getMainGeozones()
    {
        $account = $this->getAccount();
        $zones_ids = $account['zg'][$this->geozones_group_id]['zns'];

        return $this->getGeozones($account['id'], $zones_ids);
    }

    public function getGusakGeozones()
    {
        $account = $this->getAccount();
        $zones_ids = $account['zg'][$this->gusaks_group_id]['zns'];

        return $this->getGeozones($account['id'], $zones_ids);
    }


    public function getGeozones($account_id, $zones_ids)
    {
        $geozones = $this->wialon->get([
            'svc' => 'resource/get_zone_data',
            'params' => json_encode([
                'itemId' => $account_id,
                'col' => $zones_ids,
                'flags' => 0,
            ])
        ]);

        return array_map(function ($geozone) {
            return [
                'name' => $geozone['n'],
                'center' => [
                    'y' => $geozone['b']['cen_y'],
                    'x' => $geozone['b']['cen_x'],
                ],
                'points' => $geozone['p']
            ];
        }, $geozones);
    }

    public function getUnits()
    {
        return $this->wialon->get([
            'svc' => 'core/search_items',
            'params' => json_encode([
                'spec' => [
                    'itemsType' => 'avl_unit',
                    'propName' => 'sys_name',
                    'propValueMask' => "*",
                    'sortType' => "sys_name",
                    'propType' => "sys_name",
                ],
                'flags' => 1,
                'force' => 0,
                'from' => 0,
                'to' => 0,
            ]),
        ]);
    }

    public function getGroups()
    {
        return $this->wialon->get([
            'svc' => 'core/search_items',
            'params' => json_encode([
                'spec' => [
                    'itemsType' => 'avl_unit_group',
                    'propName' => 'sys_name',
                    'propValueMask' => "*",
                    'sortType' => "sys_name",
                    'propType' => "sys_name",
                ],
                'flags' => 1,
                'force' => 0,
                'from' => 0,
                'to' => 0,
            ]),
        ]);
    }

    public function getGroupUnitsWithName($groupIndex)
    {
        $units = $this->getUnits();
        $groups = $this->getGroups();

        $result = $this->array_find($groups['items'], function ($element) use ($groupIndex) {
            return $element['id'] === $groupIndex;
        });

        $transport_ids = $result['u'];
        return array_filter($units['items'], function ($unit) use ($transport_ids) {
            return in_array($unit['id'], $transport_ids);
        });

    }


    public function array_find($array, $callback)
    {
        foreach ($array as $item) {
            if ($callback($item)) {
                return $item;
            }
        }
        return null;
    }




    public function writeToDB()
    {
        $transports = $this->getTransportsWithZone(41);
        $time = now();

        foreach ($transports as $car) {
            $transport = TransportState::where('transport_id', $car['transport_id'])->latest('geozone_out')->first();

            if (isset($transport) && $transport->geozone == $car['geozone']) {

                $transport->geozone_out = $time;
                $transport->save();

            } else {

                TransportState::insert([
                    'name' => $car['name'],
                    'transport_id' => $car['transport_id'],
                    'geozone' => $car['geozone'],
                    'geozone_in' => $time,
                    'geozone_out' => $time,
                ]);

            }
        }

        $transportList = TransportList::latest('id')->first();
        $collection = collect($transports)->pluck('transport_id')->toArray();

        if (isset($transportList)) {
            $transportList->tranports = $collection;
            $transportList->save();
        } else {
            TransportList::create(['tranports' => $collection]);
        }

        $transports75 = $this->getTransportsWithZone(75);
        $transports100 = $this->getTransportsWithZone(100);
        $transports150 = $this->getTransportsWithZone(150);
        // 
        $service = new Muruntau();
        $service->writeDb($transports75, 'ReportStaysMekhanizmsLastCordinate');
        $service->writeDb($transports100, 'ReportStaysMekhanizmsLastCordinate50');
        $service->writeDb($transports150, 'ReportStaysMekhanizmsLastCordinate75');
        // 

        return DB::table('transports')->insert($transports);
    }



    private function customZones(){
        if(empty(env('DB_DATABASE_ONLINE'))) return collect([]);

        return collect([
            [
                'speed' => 0,
                'y' => 41.503154,
                'x' => 64.587814,
                'time_message' => now()->format('Y-m-d H:i:s'),
                'name' => 'Экскаватор C | КННК	(руда)',
                'transport_id' => 1,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'speed' => 0,
                'y' => 41.507430,
                'x' => 64.562078,
                'time_message' => now()->format('Y-m-d H:i:s'),
                'name' => 'Экскаватор А | ЦПТ «Северный» (вскрыша)',
                'transport_id' => 2,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'speed' => 0,
                'y' => 41.481487,
                'x' => 64.584909,
                'time_message' => now()->format('Y-m-d H:i:s'),
                'name' => 'Экскаватор В1 |ЦПТ «Южный» (вскрыша)',
                'transport_id' => 3,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'speed' => 0,
                'y' => 41.478547,
                'x' => 64.584184,
                'time_message' => now()->format('Y-m-d H:i:s'),
                'name' => 'Экскаватор В2 |ЦПТ «Южный» (вскрыша)',
                'transport_id' => 4,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}