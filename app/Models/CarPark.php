<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

class CarPark extends Model
{
    use HasFactory;

    public function parkingSpaces(): HasMany
    {
        return $this->hasMany(ParkingSpace::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, ParkingSpace::class);
    }

    public function spacesAvailable(): Attribute
    {
        return Attribute::make(get: fn () => $this->parkingSpaces->count());
    }

    public function price(): Attribute
    {
        return Attribute::make(get: fn () => $this->prices->sum('price'));
    }

    public function available(Carbon $from, Carbon $to): self
    {
        return $this->load([
            'parkingSpaces' => fn (HasMany $query) =>
                $query->available($from, $to),
            'prices' => fn (HasMany $query) =>
                $query->whereBetween('date', [$from, $to]),
        ]);
    }
}
