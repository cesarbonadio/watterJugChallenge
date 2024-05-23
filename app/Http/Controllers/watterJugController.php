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
        try {
            $service_response = (object)WatterJugService::compute(
                $request->x_capacity,
                $request->y_capacity,
                $request->z_amount_wanted
            );

            return response()->json(
                $service_response, 
                $service_response->status_code
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'status_code' => 500,
                'data' => [],
                'metadata' => [
                    'error' => $e->getMessage()
                ]
            ], 500);
        }
    }
}