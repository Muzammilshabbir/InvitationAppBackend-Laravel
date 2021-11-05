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

Route::post('/invite', [AdminController::class,'invite']);
Route::post('/register/{token}',[AuthController::class,'register']);

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/confirm-pin',[AuthController::class,'confirmPin']);
    Route::post('update-profile',[UserController::class,'updateProfile']);
});
