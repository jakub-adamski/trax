<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CarTrip
 *
 * @property integer id
 * @property integer car_id
 * @property float miles
 * @property string date
 *
 * @property Car car
 *
 * @mixin Builder
 */
class CarTrip extends Model
{
    use HasFactory;

    public $table = 'cars_trips';

    protected $fillable = [
        'car_id',
        'miles',
        'date'
    ];

    protected $casts = [
        'id' => 'integer',
        'car_id' => 'integer',
        'miles' => 'float',
        'date' => 'string',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
