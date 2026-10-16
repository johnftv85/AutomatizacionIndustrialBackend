<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user/{id}', function($id) { 
    return response()->json([
        'ok' => 200,
        'message' => 'Usuario con id: ' . $id
    ],200);
});

Route::put('/user/{id}', function($id) { 
    return response()->json([
        'ok' => 200,
        'message' => 'Usuario actualizado id: ' . $id
    ],200);    
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
