<?php

namespace Tests\Feature;

use App\Models\CarPark;
use App\Models\ParkingSpace;
use App\Models\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarParkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_car_parks(): void
    {
        $this->seed();

        $this->getJson('api/car-park')
            ->assertSuccessful()
            ->assertJsonCount(CarPark::all()->count());
    }
}
