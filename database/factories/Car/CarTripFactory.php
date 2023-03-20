<?php

namespace Database\Factories\Car;

use App\Models\Car\Car;
use App\Models\Car\CarTrip;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarTripFactory extends Factory
{
    protected $model = CarTrip::class;

    public function definition(): array
    {
        return [
            'car_id' => function () {
                return Car::factory()->create()->id;
            },
            'miles' => $this->faker->randomFloat(2, 0, 1000),
            'date' => $this->faker->date(),
        ];
    }
}
