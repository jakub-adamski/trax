<?php

namespace Tests\Feature\Api\Car;

use App\Http\Requests\Api\Car\CarTripDeleteRequest;
use App\Models\Car\Car;
use App\Models\Car\CarTrip;
use App\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Tests\TestCase;

class CarTripControllerTest extends TestCase
{
    use WithFaker, DatabaseMigrations, InteractsWithDatabase, DatabaseTransactions;

    private Car $car;
    private CarTrip $carTrip;

    // Method names according to PHPUnit convention
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // create a car for the User
        $this->car = Car::factory()->create(['user_id' => $user->id]);

        // create a car trip for the car
        $this->carTrip = CarTrip::factory()->create(['car_id' => $this->car->id]);
    }

    /** @test */
    public function it_deletes_the_car_trip_from_the_database()
    {
        $request = new CarTripDeleteRequest();
        $request->setRouteResolver(function () {
            return Route::currentRouteAction();
        });
        $request->merge(['car_trip_id' => $this->carTrip->id]);
        $this->app->instance(Request::class, $request);

        $response = $this->deleteJson("/api/car/trip/delete/{$this->carTrip->id}");

        $response->assertStatus(Response::HTTP_OK);

        $this->assertModelMissing($this->carTrip);
    }

    /** @test */
    public function it_returns_a_404_error_if_the_car_trip_does_not_exist()
    {
        $response = $this->deleteJson('/api/car/trip/delete/123');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'Car trip does not exist.',
                'errors' => [
                    'car_trip_id' => [
                        'Car trip does not exist.'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_a_405_error_if_the_authenticated_user_does_not_own_the_car_trip()
    {
        // create a car trip for another user's car
        $car = Car::factory()->create();
        $carTrip = CarTrip::factory()->create(['car_id' => $car->id]);

        $response = $this->deleteJson("/api/car/trip/delete/{$carTrip->id}");

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
