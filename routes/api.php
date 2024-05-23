<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\watterJugController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', [watterJugController::class, 'index']);
Route::post('/watterjug', [watterJugController::class, 'compute']);