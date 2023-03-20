<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Car\CarCreateRequest;
use App\Http\Requests\Api\Car\CarDeleteRequest;
use App\Models\Car\Car;
use App\Models\Car\CarBrand;
use App\Models\Car\CarModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CarController extends Controller
{
    protected Car $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function get($id = null): JsonResponse
    {
        try {
            if ($id !== null) {
                $car = $this->car->with('brand', 'model')->findOrFail($id);

                if ($car->user->id !== Auth::id()) {
                    return response()->json(['error' => 'Not Found'], 404);
                }

                return response()->json(['data' => $car]);
            }

            return response()->json(['data' => $this->car->with('brand', 'model')->where('user_id', Auth::id())->get()]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Not Found'], 404);
        }
    }

    public function create(CarCreateRequest $request): JsonResponse
    {
        $carBrand = (new CarBrand)->where('name_slug', Str::slug($request->make))->first();

        if (!$carBrand) {
            $carBrand = new CarBrand();
            $carBrand->name = $request->make;
            $carBrand->save();
        }

        $carModel = (new CarModel)->where('name_slug', Str::slug($request->model))->first();

        if (!$carModel) {
            $carModel = new CarModel();
            $carModel->name = $request->model;
            $carModel->save();
        }

        $car = new Car();
        $car->user_id = Auth::id();
        $car->brand_id = $carBrand->id;
        $car->model_id = $carModel->id;
        $car->year = $request->year;
        $car->save();

        return response()->json(['data' => $car]);
    }

    public function delete(CarDeleteRequest $request): JsonResponse
    {
        $car = $this->car->find($request->car_id);

        if ($car->user->id !== Auth::id()) {
            return response()->json(['error' => 'Not Allowed'], 405);
        }

        $car->delete();

        return response()->json(['message' => 'Car deleted successfully']);
    }
}
