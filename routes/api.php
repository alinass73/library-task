<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

 
Route::get('/s',[AuthController::class,'s'])->middleware(['auth:sanctum','admin']);

Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum')->name('logout');

Route::middleware(['auth:sanctum','admin'])->group(function(){
    Route::post('/entrydata',[AdminController::class,'dataEntryStore']);
    Route::get('/reader',[AdminController::class,'indexOfReaders']);
    Route::get('/reader/{read}',[AdminController::class,'showReader']);
});


Route::prefix('book')->middleware(['auth:sanctum'])->group(function () {
    Route::put('/update/{book}',[BookController::class,'update']);
    Route::get('/',[BookController::class,'index']);
    Route::post('/store',[BookController::class,'store']);
    Route::get('/{book}',[BookController::class,'show']);
    Route::delete('/delete/{book}',[BookController::class,'destroy']);
});

Route::get('/email/verify/{id}/{hash}',function(EmailVerificationRequest $request){
    try{
 
        $request->fulfill();
        return response()->json(['status'=>true, 'message'=>'sucessfully']);
    }catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
})->middleware(['auth:sanctum'])->name('verification.verify');

