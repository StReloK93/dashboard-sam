<?php

namespace App\Wialon;
use Session;
use Http;

class WialonAuth
{
    
    private static $instance;
    private $baseUrl = 'http://wl.ngmk.uz/wialon/ajax.html';

    function __construct()
    {
        if (Session::get('eid') == null) $this->login();
    }

    public static function getInstance()
    {
        if (!self::$instance) self::$instance = new self();

        return self::$instance;
    }

    public function sid()
    {
        return Session::get('eid');
    }

    public function get($object){

        $resdata = $this->quest($object);
        if (isset($resdata['error']) && $object['svc'] != 'token/login') {
            $this->login();
            $resdata = $this->quest($object);
        }

        return $resdata;
    }

    function quest($object){
        $response = Http::get($this->baseUrl, [...$object,'sid' => $this->sid()]);
        return $response->json();
    }

    private function login(){
        $data = $this->get([
            'svc' => 'token/login',
            'params' => json_encode([
                "token" => env('WIALON_TOKEN'),
            ]),
        ]);
        if(isset($data['eid'])){
            Session::put('eid', $data['eid']);
            Session::save();
        }
    }

}