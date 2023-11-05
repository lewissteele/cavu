<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\CarPark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    public function index(CarPark $carPark): JsonResponse
    {
        return response()->json($carPark->bookings()->get());
    }

    public function store(
        StoreBookingRequest $request,
        CarPark $carPark,
    ): Response {
        $space = $carPark->parkingSpaces()->available(
            $request->date('from'),
            $request->date('to'),
        )->firstOrFail();

        $space->bookings()->create($request->validated());

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function show(CarPark $carPark, Booking $booking): JsonResponse
    {
        return response()->json($booking);
    }

    public function update(
        UpdateBookingRequest $request,
        CarPark $carPark,
        Booking $booking,
    ): Response {
        $booking->updateOrFail($request->validated());

        return response()->noContent();
    }

    public function destroy(CarPark $carPark, Booking $booking): Response
    {
        $booking->deleteOrFail();

        return response()->noContent();
    }
}
