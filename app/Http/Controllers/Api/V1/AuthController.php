<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private UserService $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService= $userService;
    }

    public function login(Request $request)
    {
        $data= [];
        try{
            $data = $this->userService->login($request);
            return Response::Success($data['user'], $data['message']);
        }catch(\Throwable $th){
            $message= $th->getMessage();
            return Response::Error($data,$message);
        }
    }
 

    public function s(){
         
        return auth()->user()->role->name;
    }

    public function register(Request $request)
    {
        $data= [];
        try{
            
            $data = $this->userService->register($request->validate([
                'name'=>['required'],
                'password'=>['required','confirmed'],
                'email'=>['required','email'],
            ]));
            // return $data['user'];
            return Response::Success($data['user'], $data['message']);
            }catch(\Throwable $th){
            $message= $th->getMessage();
            return Response::Error($data,$message);
        }
    }
     

    public function logout()
    {
        $data= [];
        try{
            $data = $this->userService->logout();
            return Response::Success($data['user'], $data['message'],$data['code']);
        }catch(\Throwable $th){
            $message= $th->getMessage();
            return Response::Error($data,$message);
        }
    }

    
}
