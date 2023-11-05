<?php

namespace App\Http\Controllers;

use App\Models\CarPark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarParkController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(CarPark::all());
    }

    public function show(Request $request, CarPark $carPark): JsonResponse
    {
        $from = $request->date('from');
        $to = $request->date('to');

        if ($from && $to) {
            return response()->json(
                $carPark->available($from, $to)
                    ->append(['price', 'spaces_available']),
            );
        }

        return response()->json($carPark);
    }
}
