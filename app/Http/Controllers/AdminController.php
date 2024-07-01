<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReadResource;
use App\Models\Read;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function indexOfReaders(){
        $readers=Read::paginate(10);
        return ReadResource::collection($readers);
    }
    
    public function showReader(Read $read){
        return new ReadResource($read);
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
