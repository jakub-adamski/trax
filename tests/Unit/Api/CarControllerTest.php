<?php

namespace Tests\Unit\Api;

use App\Models\Car\Car;
use App\Models\Car\CarBrand;
use App\Models\Car\CarModel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // Method names according to PHPUnit convention
    public function test_create_car(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $make = $this->faker->word;
        $model = $this->faker->word;
        $year = $this->faker->numberBetween(1900, date('Y'));

        $response = $this->postJson('/api/car/create', [
            'make' => $make,
            'model' => $model,
            'year' => $year,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'brand_id',
                    'model_id',
                    'year',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $this->assertDatabaseHas('cars', [
            'user_id' => $user->id,
            'brand_id' => (new CarBrand)->where('name_slug', Str::slug($make))->first()->id,
            'model_id' => (new CarModel)->where('name_slug', Str::slug($model))->first()->id,
            'year' => $year,
        ]);
    }

    public function test_delete_car(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/car/delete/' . $car->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Car deleted successfully'
            ]);

        $this->assertDatabaseMissing('cars', [
            'id' => $car->id
        ]);
    }
}
