<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    private $service;
    public function __construct (UserService $userService) {

        $this->service = $userService;

    }


    public function Login(LoginRequest $request)
    {

        return $this->service->login($request);
    
    }


    public function register(RegisterRequest $request)
    {
        return $this->service->register($request);
    }


    public function logout(Request $request)
    {
        
        return $this->service->logout($request);

    }


    public function getUser(Request $request){
 
        return $this->service->getUser($request);

    }

    public function setShop($shopid){
 
        return $this->service->setShop($shopid);

    }

    public function passwordReset(PasswordResetRequest $request){
        return $this->service->passwordReset($request);
    }


    public function logoutUser(Request $request){
        return $this->service->logout($request);
    }

}
