<?php

namespace Database\Seeders;

use App\Models\CarPark;
use App\Models\ParkingSpace;
use App\Models\Price;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class CarParkSeeder extends Seeder
{
    public function run(): void
    {
        $carPark = CarPark::factory()
            ->has(ParkingSpace::factory(10)->hasBookings(5))
            ->create();

        $period = CarbonPeriod::create(
            now(),
            now()->addYear(),
        );

        foreach ($period as $date) {
            Price::factory([
                'car_park_id' => $carPark->id,
                'date' => $date,
            ])->create();
        }
    }
}
