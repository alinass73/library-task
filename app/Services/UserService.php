<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
 

class UserService
{
    public function register( $request)
    {
        $user=User::create([
            'name'=>$request['name'],
            'email'=>$request['email'],
            'password'=>bcrypt($request['password']),
            'role_id'=>3
        ]);
        $message='User Created Successfully';
        $user['token']= $user->createToken("token")->plainTextToken;
        $user->sendEmailVerificationNotification();
        return ['user'=>$user,'message'=>$message];
    }
    
    public function login($request)
    {
         $user = User::where('email', $request->email)->first();
        if(!is_null($user))
        {
            if(!Auth::attempt($request->only(['email','password']))){
                
                $message= "Password not true";
                $code= 401;
            }else{
                $user['token']=$user->createToken("API TOKEN")->plainTextToken;
                $message= "login successfully";
                $code= 200;
            }

    }else{
        $message= 'User not found';
        $code= 404;
    }
        
        return ['user'=>$user,'message'=>$message,'code'=>$code];
    }

    public function logout()
    {
        $user= auth()->user();
        if(!is_null(auth()->user())){
            Auth::user()->currentAccessToken()->delete() ;
            $message= 'User logged out successfully';
            $code = 200;
        }else{
            $message= 'invalid token';
            $code= 404;
        }
        return ['user'=>$user, 'message'=>$message,'code'=>$code];
    }
}