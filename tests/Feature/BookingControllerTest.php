<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\ParkingSpace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_bookings_by_id(): void
    {
        $booking = Booking::factory()
            ->for(ParkingSpace::factory()->forCarPark())
            ->create();

        $uri = route('car-park.booking.show', [
            'booking' => $booking,
            'car_park' => $booking->parkingSpace->carPark,
        ]);

        $this->getJson($uri)
            ->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $booking->id)
                    ->where('from', $booking->from->toISOString())
                    ->where('to', $booking->to->toISOString())
                    ->etc(),
            );
    }

    public function test_it_retrieves_all_bookings(): void
    {
        $this->seed();

        $this->getJson('api/car-park/1/booking')
            ->assertSuccessful()
            ->assertJsonCount(Booking::all()->count());
    }

    public function test_it_deletes_bookings_from_db(): void
    {
        $booking = Booking::factory()
            ->for(ParkingSpace::factory()->forCarPark())
            ->create();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
        ]);

        $uri = route('car-park.booking.destroy', [
            'booking' => $booking,
            'car_park' => $booking->parkingSpace->carPark,
        ]);

        $this->deleteJson($uri)->assertSuccessful();

        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    public function test_it_can_create_bookings(): void
    {
        $parkingSpace = ParkingSpace::factory()->forCarPark()->create();

        $from = now();
        $to = now()->addWeeks();

        $uri = route('car-park.booking.store', [
            'car_park' => $parkingSpace->carPark,
        ]);

        $this->postJson($uri, [
            'from' => $from,
            'to' => $to,
        ])->assertCreated();

        $this->assertDatabaseHas('bookings', [
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function test_it_can_not_double_book(): void
    {
        $from = now();
        $to = now()->addWeeks();

        $parkingSpace = ParkingSpace::factory()
            ->forCarPark()
            ->hasBookings(1, [
                'from' => $from,
                'to' => $to,
            ])
            ->create();

        $uri = route('car-park.booking.store', [
            'car_park' => $parkingSpace->carPark,
        ]);

        $this->postJson($uri, [
            'from' => $from,
            'to' => $to,
        ])->assertNotFound();
    }

    public function test_it_updates_bookings(): void
    {
        $from = now();
        $to = now()->addWeeks();

        $booking = Booking::factory()
            ->for(ParkingSpace::factory()->forCarPark())
            ->create();

        $uri = route('car-park.booking.update', [
            'booking' => $booking,
            'car_park' => $booking->parkingSpace->carPark,
        ]);

        $this->putJson($uri, [
            'from' => $from,
            'to' => $to,
        ])->assertSuccessful();

        $this->assertDatabaseHas('bookings', [
            'from' => $from,
            'id' => $booking->id,
            'to' => $to,
        ]);
    }
}
