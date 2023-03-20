<?php

namespace Database\Factories\Car;

use App\Models\Car\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CarModel>
 */
class CarModelFactory extends Factory
{
    protected string $model = CarModel::class;

    public function definition(): array
    {
        $modelName = $this->faker->word;

        return [
            'name' => $modelName,
            'name_slug' => Str::slug($modelName),
        ];
    }
}
