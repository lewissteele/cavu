<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        $from = fake()->dateTimeThisYear('+1 month');
        $to = Carbon::parse($from)->addWeeks();

        return [
            'from' => $from,
            'to' => $to,
        ];
    }
}
