<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',[AuthController::class,'login'])->middleware('checkStatus');
Route::post('/register/{token}',[AuthController::class,'register']);

Route::post('/confirm-pin',[AuthController::class,'confirmPin'])->middleware('auth:sanctum');

Route::group(['middleware'=>['auth:sanctum','checkStatus']],function(){
    
    Route::post('update-profile',[AuthController::class,'updateProfile']);
    Route::post('/logout',[AuthController::class,'logout']);
});

Route::group(['middleware'=>['auth:sanctum','checkRole']],function(){

    Route::post('/invite', [AdminController::class,'invite']);

});
