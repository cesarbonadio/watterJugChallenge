<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\watterJugRequest;
use App\Services\WatterJugService;

class watterJugController extends Controller
{
    // just a health check function
    public function index() {
        return response()->json(["message" => "ok"], 200);
    }

    // the important compute function
    public function compute(watterJugRequest $request) {
        return response()->json([
            WatterJugService::compute(
                $request->x_capacity,
                $request->y_capacity,
                $request->z_amount_wanted
            )
        ], 200);
    }
}