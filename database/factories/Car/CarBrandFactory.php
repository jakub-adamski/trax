<?php

namespace Database\Factories\Car;

use App\Models\Car\CarBrand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CarBrand>
 */
class CarBrandFactory extends Factory
{
    protected $model = CarBrand::class;

    public function definition(): array
    {
        $brandName = $this->faker->company;

        return [
            'name' => $brandName,
            'name_slug' => Str::slug($brandName),
        ];
    }
}
