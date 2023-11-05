<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarParkRequest;
use App\Http\Requests\UpdateCarParkRequest;
use App\Models\CarPark;
use Illuminate\Http\JsonResponse;

class CarParkController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(CarPark::all());
    }

    public function show(CarPark $carPark): JsonResponse
    {
        //
    }
}
