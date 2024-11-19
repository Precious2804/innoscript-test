<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('done', function () {
    return "okay";
});

Route::middleware([ForceJsonResponse::class])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [UserController::class, 'register']);
        Route::post('login', [UserController::class, 'login']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('info', [UserController::class, 'info']);
            Route::post('logout', [UserController::class, 'logout']);
            Route::post('update-password', [UserController::class, 'updatePassword']);
            Route::post('set-preferences', [UserController::class, 'setPreferences']);
            Route::get('get-preferences', [UserController::class, 'getPreferences']);
        });

        Route::group(['prefix' => 'article'], function () {
            Route::get('index', [ArticleController::class, 'index']);
            Route::get('show/{article}', [ArticleController::class, 'show']);
        });
    });
});
