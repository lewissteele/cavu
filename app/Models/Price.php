<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $date
 * @property float $price
 * @property int $id
 */
class Price extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
        'price' => 'float',
    ];
}
