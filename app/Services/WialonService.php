<?php

namespace App\Services;

use App\Wialon\AuthWialon;
use App\Models\Truck;

use DB;
use Carbon\Carbon;
use App\Models\TransportState;
use App\Models\TransportList;
use App\Services\GeoZoneService;

class WialonService
{
	protected $wialon;
	function __construct()
	{
		$this->wialon = AuthWialon::getInstance();
	}


	public function getTransportsWithZone()
	{
		$geoService = new GeoZoneService();

		$transports = $this->getTransportPoints(7381);
		$zones = $this->getGeozones();
		$excavators = $this->getExcavators();

		foreach ($transports as $key => $car) {
			$pointCar = ['x' => $car['x'], 'y' => $car['y']];

			$geozoneName = $geoService->findZone($pointCar, $zones);

			if ($geozoneName == null) {
				$distances = $geoService->getDistances($pointCar, $excavators);

				if ($distances[0]['distance'] < 41) {
					$transports[$key]['distance_ex'] = round($distances[0]['distance']);
					$transports[$key]['geozone'] = $distances[0]['name'];
				} else {
					$transports[$key]['distance_ex'] = null;
					$transports[$key]['geozone'] = null;
				}

			} else {
				$transports[$key]['distance_ex'] = null;
				$transports[$key]['geozone'] = $geozoneName;
			}

		}

		return array_values($transports);
	}


	public function writeToDB()
	{
		$transports = $this->getTransportsWithZone();
		$time = now();

		foreach ($transports as $key => $car) {
			$transport = TransportState::where([
				['transport_id', $car['transport_id']],
			])->latest('geozone_out')->first();

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

		$collection = collect($this->getTransportPoints(7381))->pluck('transport_id');
		TransportList::create(['tranports' => $collection]);

		return DB::table('transports')->insert($transports);
	}

	public function getExcavators()
	{
		$frontal = $this->getTransportPoints(10013);
		$excavator = $this->getTransportPoints(4076);
		$collection = collect($frontal)->merge($excavator);
		return $collection->all();
	}

	public function getTransportPoints($groupIndex)
	{
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

			$transports[$key]['name'] = $value['nm'];
			$transports[$key]['transport_id'] = $value['id'];
			$transports[$key]['y'] = $point['messages'][0]['pos']['y'];
			$transports[$key]['x'] = $point['messages'][0]['pos']['x'];
			$transports[$key]['time_message'] = Carbon::createFromTimestamp($point['messages'][0]['t'])->toDateTimeString();
			$transports[$key]['created_at'] = $created;

		}

		return $transports;
	}

	public function getGeozones()
	{
		$data = $this->wialon->get([
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
		$qaynarovId = $data['items'][1]['id'];
		$qaynarovZonesGroup = $data['items'][1]['zg'][1]['zns'];
		$geozones = $this->wialon->get([
			'svc' => 'resource/get_zone_data',
			'params' => json_encode([
				'itemId' => $qaynarovId,
				'col' => $qaynarovZonesGroup,
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


	public function setTypes()
	{
		$groups = $this->getGroups();

		$list = [9748, 9749, 9750, 9751];

		foreach ($groups['items'] as $key => $group) {
			if (in_array($group['id'], $list)) {

				foreach ($group['u'] as $key => $value) {
					Truck::updateOrCreate(
						['transport_id' => $value],
						['group_name' => $group['nm'], 'group_id' => $group['id']]
					);
				}

			}
		}
	}

}





// 4076 ekskavatorlar
// 7381 Hamma avtoagdargichlar







// Функция сравнения для usort()


// Применение сортировки
// usort($array, function($a, $b) {
//     // Сначала сравниваем значения "geozone"
//     $compareGeozone = strcmp($a["geozone"], $b["geozone"]);

//     // Если "geozone" равны, сравниваем значения "transport"
//     if ($compareGeozone === 0) {
//         return strcmp($a["transport"], $b["transport"]);
//     }

//     return $compareGeozone;
// });