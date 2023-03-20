<?php

namespace App\Models\Car;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Car
 *
 * @property integer id
 * @property integer user_id
 * @property integer brand_id
 * @property integer model_id
 * @property integer year
 *
 * @property User user
 * @property CarBrand brand
 * @property CarModel model
 * @property Collection|CarTrip[] trips
 *
 * @mixin Builder
 */
class Car extends Model
{
    use HasFactory;

    public $table = 'cars';

    protected $fillable = [
        'user_id',
        'brand_id',
        'model_id',
        'year'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'brand_id' => 'integer',
        'model_id' => 'integer',
        'year' => 'integer'
    ];

    protected $appends = [
        'miles_total'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(CarTrip::class);
    }

    public function getMilesTotalAttribute(): int
    {
        return $this->trips->sum('miles');
    }
}
