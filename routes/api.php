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
        Route::post('register', [UserController::class, 'register'])->name('auth.register');
        Route::post('login', [UserController::class, 'login'])->name(('auth.login'));
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('info', [UserController::class, 'info'])->name('user.info');
            Route::post('logout', [UserController::class, 'logout'])->name('user.logout');
            Route::put('update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
            Route::put('set-preferences', [UserController::class, 'setPreferences'])->name('user.setPreferences');
            Route::get('get-preferences', [UserController::class, 'getPreferences'])->name('user.getPreferences');
        });

        Route::group(['prefix' => 'article'], function () {
            Route::get('index', [ArticleController::class, 'index'])->name('article.index');
            Route::get('show/{article}', [ArticleController::class, 'show'])->name('article.show');
            Route::get('preferences', [ArticleController::class, 'preferences'])->name('article.preferences');
        });
    });
});
