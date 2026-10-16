<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EnterpriceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', [UserController::class, 'index']);

// Route::post('/user', [UserController::class, 'store']);

// Route::get('/user/{id}', [UserController::class, 'show']);

// Route::patch('/user/{id}', [UserController::class, 'update']);

// Route::delete('/user/{id}', [UserController::class, 'destroy']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('user', UserController::class);
Route::apiResource('enterprice', EnterpriceController::class);
