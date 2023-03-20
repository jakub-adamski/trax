<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Car\CarTripCreateRequest;
use App\Http\Requests\Api\Car\CarTripDeleteRequest;
use App\Models\Car\CarTrip;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarTripController extends Controller
{
    protected CarTrip $carTrip;

    public function __construct(CarTrip $carTrip)
    {
        $this->carTrip = $carTrip;
    }

    public function get($id = null): JsonResponse
    {
        try {
            if ($id !== null) {
                // Normally eloquent would be used
                //$carTrip = $this->carTrip->findOrFail($id); //throws exception

                // For the purpose of the task, query written in clean SQL
                $carTrip = DB::selectOne('SELECT * FROM ' . $this->carTrip->getTable() . ' WHERE id = ? LIMIT 1', [$id]);

                // Since findOrFail throws an exception, we throw it here too
                if (is_null($carTrip)) {
                    throw (new ModelNotFoundException)->setModel(
                        get_class($this->carTrip), $id
                    );
                }

                if ($carTrip->car->user->id !== Auth::id()) {
                    return response()->json(['error' => 'Not Allowed'], 405);
                }

                return response()->json(['data' => $carTrip]);
            }

            // Normally eloquent would be used - and that would be enough
            // $carsTrips = $this->carTrip->with('car', 'car.brand', 'car.model')->whereHas('car', static function ($query) {
            //      return $query->where('cars.user_id', Auth::id());
            // })->get()->toArray();

            // For the purpose of the task, query written by builder
            $carsTrips = DB::table('cars_trips')
                ->select('cars_trips.*', 'cars_models.name as car_model', 'cars_brands.name as car_brand', 'cars.year as car_year', DB::raw('SUM(ct.miles) as miles_total'))
                ->leftJoin('cars', 'cars_trips.car_id', '=', 'cars.id')
                ->leftJoin('cars_models', 'cars.model_id', '=', 'cars_models.id')
                ->leftJoin('cars_brands', 'cars.brand_id', '=', 'cars_brands.id')
                ->leftJoin('cars_trips as ct', 'ct.car_id', '=', 'cars.id')
                ->where('cars.user_id', '=', Auth::id())
                ->groupBy('cars_trips.id')
                ->orderBy('cars_trips.id', 'desc')
                ->get();

            $carTripsTransformed = [];

            foreach ($carsTrips as $carTrip) {
                $carTripsTransformed[] = [
                    'id' => $carTrip->id,
                    'date' => $carTrip->date,
                    'miles' => $carTrip->miles,
                    'car' => [
                        'id' => $carTrip->car_id,
                        'brand' => [
                            'name' => $carTrip->car_brand
                        ],
                        'model' => [
                            'name' => $carTrip->car_model
                        ],
                        'year' => $carTrip->car_year,
                        'miles_total' => $carTrip->miles_total
                    ],
                ];
            }

            return response()->json(['data' => $carTripsTransformed /*$carsTrips*/]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Not Found'], 404);
        }
    }

    public function create(CarTripCreateRequest $request): JsonResponse
    {
        $carTrip = $this->carTrip->create([
            'car_id' => $request->car_id,
            'miles' => $request->miles,
            'date' => Carbon::parse($request->date)->format('Y-m-d H:i:s')
        ]);

        return response()->json(['data' => $carTrip]);
    }

    public function delete(CarTripDeleteRequest $request): JsonResponse
    {
        $carTrip = $this->carTrip->find($request->car_trip_id);

        if ($carTrip->car->user->id !== Auth::id()) {
            return response()->json(['error' => 'Not Allowed'], 405);
        }

        $carTrip->delete();

        return response()->json(['message' => 'Car trip deleted successfully']);
    }
}
