<?php

namespace Tests\Feature;

use App\Models\CarPark;
use App\Models\ParkingSpace;
use App\Models\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CarParkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_pricing_and_availability(): void
    {
        $price = fake()->randomFloat(2);
        $from = now();
        $to = now()->addDay();

        $carPark = CarPark::factory()
            ->has(ParkingSpace::factory())
            ->has(Price::factory(['price' => $price, 'date' => $from]))
            ->create();

        $uri = route('car-park.show', [
            'car_park' => $carPark,
            'from' => $from->toISOString(),
            'to' => $to->toISOString(),
        ]);

        $this->getJson($uri)
            ->assertSuccessful()
            ->assertJsonPath('price', $price)
            ->assertJsonPath('spaces_available', 1);
    }

    public function test_index_returns_all_car_parks(): void
    {
        $this->seed();

        $this->getJson('api/car-park')
            ->assertSuccessful()
            ->assertJsonCount(CarPark::all()->count());
    }

    public function test_bookings_reduce_availability(): void
    {
        $price = fake()->randomFloat(2);
        $from = now();
        $to = now()->addDay();

        $carPark = CarPark::factory()
            ->has(ParkingSpace::factory())
            ->has(Price::factory(['price' => $price, 'date' => $from]))
            ->create();

        $uri = route('car-park.show', [
            'car_park' => $carPark,
            'from' => $from->toISOString(),
            'to' => $to->toISOString(),
        ]);

        $this->getJson($uri)
            ->assertSuccessful()
            ->assertJsonPath('spaces_available', 1);

        $carPark->parkingSpaces()->first()->bookings()->create([
            'from' => $from,
            'to' => $to,
        ]);

        $this->getJson($uri)
            ->assertSuccessful()
            ->assertJsonPath('spaces_available', 0);
    }

    public function test_it_can_fetch_car_parks_by_id(): void
    {
        $carPark = CarPark::factory()->create();

        $uri = route('car-park.show', [
            'car_park' => $carPark,
        ]);

        $this->getJson($uri)
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $carPark->id)->etc(),
            );
    }
}
