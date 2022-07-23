<?php

use App\Http\Controllers\API\AdsController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CkeditorController;
use App\Models\Article;
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

Route::group(['prefix' => 'articles'], function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('/{id}', [ArticleController::class, 'show']);
    Route::post('/', [ArticleController::class, 'store']);
    Route::put('/{id}', [ArticleController::class, 'update']);
    Route::delete('/{id}', [ArticleController::class, 'destroy']);
});

Route::group(['prefix' => 'bookmarks'], function () {
    Route::post('/', [BookmarkController::class, 'store']);
    Route::get('/', [BookmarkController::class, 'index']);
    Route::delete('/{article_id}', [BookmarkController::class, 'destroy']);
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

Route::group(['prefix' => 'ads'], function () {
    Route::get('/', [AdsController::class, 'index']);
    Route::get('/{id}', [AdsController::class, 'show']);
    Route::post('/', [AdsController::class, 'store']);
    Route::put('/{id}', [AdsController::class, 'update']);
    Route::delete('/{id}', [AdsController::class, 'destroy']);
});
