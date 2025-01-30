<?php

namespace App\Wialon;
use App\Wialon\WialonAuth;
use Carbon\Carbon;
use Log;


class WialonApi
{
   protected $wialon, $allUnits, $getGroups, $account;

   function __construct()
   {
      $this->wialon = WialonAuth::getInstance();

      $this->allUnits = $this->getAllUnits();
      $this->getGroups = $this->getGroups();
      $this->account = $this->getAccount();
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
      $units = $this->wialon->get([
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
      Log::info('items', ['rep' => $units]);
      return $units['items'];
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


   public function getAccountGeozonesID()
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
            'flags' => 4097,
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
      ])['items'];
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
            'flagsMask' => 65281,
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
            'flags' => 4097,
            'from' => 0,
            'to' => 0,
         ]),
      ]);
   }




   public function getGroupById($group_id)
   {
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
            'flags' => 1033,
            'from' => 0,
            'to' => 0,
         ]),
      ]);
   }


   public function groupTransportWithLastMessage($wialonGroupID)
   {
      if ($wialonGroupID == 0) return [];


      $allUnits = collect($this->allUnits);
      $groups = collect($this->getGroups);

      $transportIds = $groups->firstWhere('id', $wialonGroupID)['u'] ?? [];
      $groupUnits = array_map(fn($id) => $allUnits->first(fn($unit) => $unit['id'] == $id), $transportIds);


      $transports = [];

      foreach ($groupUnits as $key => $unit) {
         $point = $this->lastMessage($unit['id'], now()->timestamp);

         if (count($point['messages']) != 0) {
            $transports[$key]['speed'] = $point['messages'][0]['pos']['s'];
            $transports[$key]['y'] = $point['messages'][0]['pos']['y'];
            $transports[$key]['x'] = $point['messages'][0]['pos']['x'];
            $transports[$key]['time_message'] = Carbon::createFromTimestamp($point['messages'][0]['t'])->toDateTimeString();
            $transports[$key]['name'] = $unit['nm'];
            $transports[$key]['transport_id'] = $unit['id'];
            $transports[$key]['created_at'] = now()->format('Y-m-d H:i:s');
         }
      }

      return $transports;
   }


   public function getGeozonesGroup($groupIndex)
   {
      if ($groupIndex == 0) return [];

      $account = $this->account;
      $zones_ids = $account['zg'][$groupIndex]['zns'];

      return $this->getAccountGeozones($account['id'], $zones_ids);
   }






   

   // public function messagesFormatter($messages, $transport_id)
   // {
   //    $array = [];
   //    $sensor = $this->getSensors($transport_id);
   //    foreach ($messages as $message) {
   //       $sensor_value = $sensor && isset($message['p'][$sensor]) ? $message['p'][$sensor] : null;
   //       $cuzov_value = null;
   //       if ($sensor_value) {
   //          if ($sensor_value > 10000 && $sensor_value < 20000) {
   //             $cuzov_value = 1;
   //          }
   //          if ($sensor_value > 20000) {
   //             $cuzov_value = 2;
   //          }
   //       }

   //       $array[] = [
   //          'speed' => $message['pos']['s'],
   //          'y' => $message['pos']['y'],
   //          'x' => $message['pos']['x'],
   //          'time' => $message['t'],
   //          'cuzov' => $cuzov_value,
   //          'sensor_name' => $sensor,
   //          'geozone' => null,
   //       ];

   //    }
   //    return $array;
   // }

   // public function getSensors($transport_id)
   // {
   //    $response = $this->getTransportSensors($transport_id);

   //    if (isset($response['items'][0]['sens'])) {
   //       $select = collect($response['items'][0]['sens'])->firstWhere('n', 'Датчик подъема Кузова');
   //       if (isset($select)) {
   //          return explode('/', $select['p'])[0] ?? $select['p'];
   //       } else
   //          return null;
   //    } else
   //       return null;
   // }












      // public function importGeozonesKML()
   // {
   //    return $this->wialon->get([
   //       'svc' => 'exchange/export_zones',
   //       'params' => json_encode([
   //          'fileName' => 'asasd',
   //          'zones' => [
   //             [
   //                'itemId' => 1,
   //                'id' => 1,
   //             ],
   //          ],
   //          'compress' => 1,
   //       ]),
   //    ]);
   // }

}