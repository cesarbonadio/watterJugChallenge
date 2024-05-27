<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\waterJugController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', [waterJugController::class, 'index']);
Route::post('/waterjug', [waterJugController::class, 'compute']);