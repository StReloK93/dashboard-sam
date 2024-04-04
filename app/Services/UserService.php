<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Auth;
use Hash;
class UserService {

    public function login($request) {

        $user = User::where('login', $request->login)->first();
 
        if (Hash::check($request->password, $user->password)) {
            $token = $this->createToken($user);
            return response()->json(['token' => $token,'type' => 'Bearer'], 200);
        }
        else{
            return response()->json(['message' => 'Parol yoki login xato!'], 299);
        }

        // if (Auth::attempt($request->only('login', 'password'))) {

        //     $user = Auth::user();
        //     $token = $this->createToken($user);
        //     return response()->json(['token' => $token,'type' => 'Bearer'], 200);

        // }

    }

    private function createToken($user) {
        return $user->createToken('userToken', ['admin'])->plainTextToken;
    }


    public function register($request) {

        $params = $request->only('login','name','password');
        $params['password'] = Hash::make($params['password']);
        User::create($params);
        return response()->json(true, 200);
    }

    public function getUser($request){
        return $request->user();
    }

    public function setShop($shopid){
        $user = Auth::user();

        $user->active_shop = $shopid;
        $user->save();
        return $user->fresh();
    }

    public function logout($request): void {

        Auth::user()->currentAccessToken()->delete();

    }


    public function passwordReset($request) {

        $user = $request->user();
        
        if (Hash::check($request->old, $user->password) == false) {
            return response()->json(['errors' => 
                ['message' => ["hozirgi parol to'gri emas"]]
            ], 422);
        }

        $user->password = Hash::make($request->new);
        
        return $user->save();

    }
}