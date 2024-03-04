<?php

namespace App\Wialon;
use Session;
use Http;

class AuthWialon
{
    private static $instance;
    private $token = '94e3f3e1ac97def632645f3655f7c9323EBBF730196FF0171A3B0D34D08A0351D946F7FB';
    
    // 94e3f3e1ac97def632645f3655f7c9328E601C46AC4EF011096A8869F78C4BFF4B6B0920
    // 94e3f3e1ac97def632645f3655f7c932C13E9383786732094DE668C8991793307E52FD61
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
                "token" => $this->token,
            ]),
        ]);
        Session::put('eid', $data['eid']);
        Session::save();
    }

}