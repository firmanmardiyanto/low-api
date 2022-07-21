<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CkeditorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth',], function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::group(['middleware' => 'web'], function () {
        Route::get('/{provider}', [AuthController::class, 'redirectToProvider']);
        Route::get('/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('ckeditor/upload', [CkeditorController::class, 'upload']);
});
