<?php

namespace Database\Factories\Car;

use App\Models\Car\Car;
use App\Models\Car\CarBrand;
use App\Models\Car\CarModel;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    protected string $model = Car::class;

    public function definition(): array
    {
        $brandName = $this->faker->company;
        $modelName = $this->faker->word;

        $carBrand = (new CarBrand)->where('name_slug', Str::slug($brandName))->first();
        if (!$carBrand) {
            $carBrand = CarBrand::factory()->create([
                'name' => $brandName,
                'name_slug' => Str::slug($brandName)
            ]);
        }

        $carModel = (new CarModel)->where('name_slug', Str::slug($modelName))->first();
        if (!$carModel) {
            $carModel = CarModel::factory()->create([
                'name' => $modelName,
                'name_slug' => Str::slug($modelName)
            ]);
        }

        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'brand_id' => $carBrand->id,
            'model_id' => $carModel->id,
            'year' => $this->faker->numberBetween(1900, date('Y')),
        ];
    }
}
