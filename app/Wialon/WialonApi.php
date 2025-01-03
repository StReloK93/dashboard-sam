<?php

namespace App\Wialon;
use App\Wialon\WialonAuth;
use Carbon\Carbon;

class WialonApi
{
   protected $wialon;

   function __construct()
   {
      $this->wialon = WialonAuth::getInstance();
   }

   public function lastMessage($transport_id, $time)
   {
      return $this->wialon->get([
         'svc' => 'messages/load_last',
         'params' => json_encode([
            'itemId' => $transport_id,
            'lastTime' => $time,
            'lastCount' => 1,
            'flags' => 1,
            'flagsMask' => 65281,
            'loadCount' => 1,
         ]),
      ]);
   }

   public function getAllUnits()
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
            'force' => 2,
            'from' => 0,
            'to' => 0,
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

      $accounts = collect($accountsInformation['items']);
		return $accounts->first(fn($account) => $account['nm'] == env('BASE_ACCOUNT'));
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

   public function messagesInterval($transport_id, $from, $to)
   {
      return $this->wialon->get([
         'svc' => 'messages/load_interval',
         'params' => json_encode([
            'itemId' => $transport_id,
            'timeFrom' => $from,
            'timeTo' => $to,
            'flags' => 1,         // Базовые данные
            'flagsMask' => 65281, // Данные сообщений
            'loadCount' => 0xffffffff
         ]),
      ]);
   }

   public function getAccountGeozones($account_id, $geozone_ids)
   {
      $geozones = $this->wialon->get([
         'svc' => 'resource/get_zone_data',
         'params' => json_encode([
            'itemId' => $account_id,
            'col' => $geozone_ids,
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

   public function getTransportSensors($transport_id)
   {
      return $this->wialon->get([
         'svc' => 'core/search_items',
         'params' => json_encode([
            'spec' => [
               'itemsType' => 'avl_unit',
               'propName' => 'sys_id',
               'propValueMask' => $transport_id,
               'sortType' => 'unit_sensors',
            ],
            'force' => 1,
            'flags' => 4097, // Базовые данные
            'from' => 0,
            'to' => 0,
         ]),
      ]);
   }



   public function getGroupById($group_id){
      return $this->wialon->get([
         'svc' => 'core/search_items',
         'params' => json_encode([
            'spec' => [
               'itemsType' => 'avl_unit_group',
               'propName' => 'sys_id',
               'propValueMask' => $group_id,
               'sortType' => 'sys_name',
               'propType' => 'nm',
               'or_logic' => 0
            ],
            'force' => 1,
            'flags' => 1033, // Базовые данные
            'from' => 0,
            'to' => 0,
         ]),
      ]);
   }



   public function getGeozonesById($groupIndex)
	{
      if ($groupIndex == null || $groupIndex == 0) return [];

		$account = $this->getAccount();
		$zones_ids = $account['zg'][$groupIndex]['zns'];

		return $this->getAccountGeozones($account['id'], $zones_ids);
	}


   public function getGroupUnitsWithName($group_id)
	{
      if ($group_id == null || $group_id == 0) return [];

		$groups = $this->getGroupById($group_id);
		$units = $this->getAllUnits();

		return collect($units['items'])->whereIn('id', $groups['items'][0]['u'])->values()->all();
	}



   public function messagesFormatter($messages, $transport_id){
      $array = [];
      $sensor = $this->getSensors($transport_id);
      foreach ($messages as $message) {
         $sensor_value = $sensor && isset($message['p'][$sensor]) ? $message['p'][$sensor] : null;
         $cuzov_value = null;
         if($sensor_value){
            if($sensor_value > 10000 && $sensor_value < 20000){
               $cuzov_value = 1;
            }
            if($sensor_value > 20000){
               $cuzov_value = 2;
            }
         }

         $array[] = [
            'speed' => $message['pos']['s'],
            'y' => $message['pos']['y'],
            'x' => $message['pos']['x'],
            'time' => $message['t'],
            'cuzov' => $cuzov_value,
            'sensor_name' => $sensor,
            'geozone' => null,
         ];

      }
      return $array;
   }

   public function getSensors($transport_id)
	{
		$response = $this->getTransportSensors($transport_id);

		if (isset($response['items'][0]['sens'])) {
			$select = collect($response['items'][0]['sens'])->firstWhere('n', 'Датчик подъема Кузова');
			if(isset($select)){
				return explode('/', $select['p'])[0] ?? $select['p'];
			}
			else return null;
		} 
		else return null;
	}
}