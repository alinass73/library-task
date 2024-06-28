<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\BookController;
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

Route::post('/entrydata',[AuthController::class,'dataEntryStore'])->middleware(['auth:sanctum','admin']);

Route::prefix('book')->middleware(['auth:sanctum'])->group(function () {
    Route::put('/update/{book}',[BookController::class,'update']);
    Route::get('/',[BookController::class,'index']);
    Route::post('/store',[BookController::class,'store']);
    Route::get('/{book}',[BookController::class,'show']);
    Route::delete('/delete/{book}',[BookController::class,'destroy']);
});