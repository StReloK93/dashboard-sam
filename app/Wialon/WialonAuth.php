<?php

namespace App\Wialon;
use Session;
use Http;
use Log;
use Cache;
class WialonAuth
{

    private static $instance;
    private $baseUrl = 'http://wl.ngmk.uz/wialon/ajax.html';

    function __construct()
    {
        if (Cache::get('wialon_sid') == null)
            $this->login();
    }

    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    public function sid()
    {
        return Cache::get('wialon_sid');
    }

    public function get($object)
    {

        $resdata = $this->quest($object);
        if (isset($resdata['error']) && $object['svc'] != 'token/login') {
            $this->login();
            $resdata = $this->quest($object);
        }

        return $resdata;
    }

    function quest($object)
    {
        $response = Http::get($this->baseUrl, [...$object, 'sid' => $this->sid()]);
        return $response->json();
    }

    private function login()
    {
        $data = $this->get([
            'svc' => 'token/login',
            'params' => json_encode([
                "token" => env('WIALON_TOKEN'),
            ]),
        ]);
        if (isset($data['eid'])) {
            Cache::put('wialon_sid', $data['eid'], now()->addMinutes(20));
        }
    }

}