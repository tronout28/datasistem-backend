<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::group(['prefix' => '/auth'], function () {

    Route::post('/login/admin', [AuthController::class, 'loginAdmin']);
    Route::post('/login/produksi', [AuthController::class, 'loginProduksi']);
    Route::post('/login/marketing', [AuthController::class, 'loginMarketing']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::group(['prefix' => '/user', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/', [UserController::class, 'index']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/totaluser', [UserController::class, 'TotalUser']);

    Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
        Route::post('/make-account', [UserController::class, 'MakeUseraccount']);
    });
});
