<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportController;

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
    Route::get('/showmarketing', [UserController::class, 'showMarketing']);
    Route::get('/showproduksi', [UserController::class, 'showProduksi']);
    Route::put('/update-profile', [UserController::class, 'updateProfile']);

    Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
        Route::post('/make-account', [UserController::class, 'MakeUseraccount']);
    });
});

Route::group(['prefix' => '/paket', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/', [PaketController::class, 'index']);
    Route::get('/show/{id}', [PaketController::class, 'show']);

    Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
        Route::post('/make-paket', [PaketController::class, 'store']);
        Route::put('/update-paket/{id}', [PaketController::class, 'update']);
        Route::delete('/delete/{id}', [PaketController::class, 'destroy']);
    });
});

Route::group(['prefix' => '/task', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/', [TaskController::class, 'index']);
    Route::get('/show/{id}', [TaskController::class, 'show']);
    Route::get('/task-marketing', [TaskController::class, 'getTaskByMarketing']);
    Route::get('/task-produksi', [TaskController::class, 'getTaskByProduksi']);
    Route::get('/task-paket/{id}', [TaskController::class, 'getTaskByPaket']);

    Route::group(['middleware' => ['auth:sanctum', 'role:admin|marketing']], function () {
        Route::post('/make-task', [TaskController::class, 'insertTask']);
        Route::put('/update-task/{id}', [TaskController::class, 'update']);
        Route::delete('/delete/{id}', [TaskController::class, 'delete']);
    });
});

Route::group(['prefix' => '/report', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/', [ReportController::class, 'index']);
    Route::get('/show/{id}', [ReportController::class, 'getReport']);
    Route::get('/report-task/{id}', [ReportController::class, 'getReportByTask']);

    Route::group(['middleware' => ['auth:sanctum', 'role:produksi']], function () {
        Route::post('/make-report', [ReportController::class, 'inputReport']);
        Route::put('/update-report/{id}', [ReportController::class, 'updateReport']);
        Route::delete('/delete/{id}', [ReportController::class, 'deleteReport']);
    });
});
