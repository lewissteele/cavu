<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class ParkingSpace extends Model
{
    use HasFactory;

        public function carPark(): BelongsTo
    {
        return $this->belongsTo(CarPark::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeAvailable(
        Builder $query,
        Carbon $from,
        Carbon $to,
    ): void {
        $query->whereDoesntHave('bookings', fn (Builder $query) =>
            $query->whereDate('from', '<', $to)
                ->whereDate('to', '>', $from),
        );
    }
}
