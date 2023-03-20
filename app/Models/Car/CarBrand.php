<?php

namespace App\Models\Car;

use App\Interfaces\SluggableInterface;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class CarBrand
 *
 * @property integer id
 * @property string name
 * @property string name_slug
 *
 * @property Collection|Car[] cars
 *
 * @mixin Builder
 */
class CarBrand extends Model implements SluggableInterface
{
    use HasFactory;
    use Sluggable;

    public $table = 'cars_brands';

    protected $fillable = [
        'name',
        'name_slug'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'name_slug' => 'string',
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'name_slug' => [
                'source' => 'name',
            ],
        ];
    }
}
