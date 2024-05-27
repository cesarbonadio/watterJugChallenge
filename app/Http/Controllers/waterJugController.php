<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\waterJugRequest;
use App\Services\WaterJugService;
use Illuminate\Support\Facades\Redis;

class waterJugController extends Controller
{
    // just a health check function
    public function index() {
        return response()->json(["message" => "ok"], 200);
    }

    // the important compute function
    public function compute(waterJugRequest $request) {
        try {
            $redis_key = "{$request->x_capacity},{$request->y_capacity},{$request->z_amount_wanted}";

            $exists_in_redis = Redis::exists($redis_key);

            if ($exists_in_redis) {
                $decoded_object = json_decode(Redis::get($redis_key));
                return response()->json(
                    $decoded_object, 
                    $decoded_object->status_code
                );
            }

            $service_response = (object)WaterJugService::compute(
                $request->x_capacity,
                $request->y_capacity,
                $request->z_amount_wanted
            );

            if (!$exists_in_redis) {
                Redis::set($redis_key, json_encode($service_response));
            }

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