<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EnterpriceController;
use App\Http\Controllers\Api\ReadingController;
use App\Http\Middleware\DeviceBasicAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', [UserController::class, 'index']);

// Route::post('/user', [UserController::class, 'store']);

// Route::get('/user/{id}', [UserController::class, 'show']);

// Route::patch('/user/{id}', [UserController::class, 'update']);

// Route::delete('/user/{id}', [UserController::class, 'destroy']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware(DeviceBasicAuth::class);

Route::apiResource('user', UserController::class);
Route::apiResource('enterprice', EnterpriceController::class);

// Lecturas enviadas por los equipos (HTTP Basic)
Route::post('readings', [ReadingController::class, 'store'])->middleware(DeviceBasicAuth::class);
