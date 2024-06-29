<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try{
            $request->validate([
                'email'=>['required','email'],
                'password'=>['required']
            ]);
            if(!Auth::attempt($request->only(['email','password']))){
                return $this->error('message','Credentials do not match',401);
            }
            $user= User::where('email',$request->email)->first();
            
            // $user['token']=$user->createToken("API TOKEN")->plainTextToken;
            // return new UserResource($user);
            return response()->json([
                'user'=>$user,
                'token'=>$user->createToken('Api Token of '. $user->name)->plainTextToken
            ]);
        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
        ], 500);
    }
    }

    public function s(){
         
        return auth()->user()->role->name;
    }

    public function register(Request $request)
    {
        try{
            $request->validate([
                'name'=>['required'],
                'password'=>['required','confirmed'],
                'email'=>['required','email'],
            ]);
            
            $user=User::create([
                'name'=>$request['name'],
                'email'=>$request['email'],
                'password'=>bcrypt($request['password']),
                'role_id'=>3
            ]);
            // return new UserResource($user);
            return response()->json([
                    'status' => true,
                    'user'=>$user,
                    'message' => 'User Created Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }
     

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete() ;
        return response()->json([
            'message'=>'you are logged out'
        ]);
    }

    public function dataEntryStore(Request $request)
    {
        try{
            $request->validate([
                'name'=>['required'],
                'password'=>['required','confirmed'],
                'email'=>['required','email'],
            ]);
            
            $user=User::create([
                'name'=>$request['name'],
                'email'=>$request['email'],
                'password'=>bcrypt($request['password']),
                'role_id'=>2,
                'email_verified_at'=>now()
            ]);
            // return new UserResource($user);
            return response()->json([
                    'status' => true,
                    'user'=>$user,
                    'message' => 'Entry data Created Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }
}
