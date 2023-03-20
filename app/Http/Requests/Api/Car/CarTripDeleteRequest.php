<?php

namespace App\Http\Requests\Api\Car;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int car_trip_id
 */
class CarTripDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['car_trip_id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'car_trip_id' => 'required|integer|exists:cars_trips,id',
        ];
    }

    /**
     * @todo translations should be moved to the lang folder - this is just example
     */
    public function messages(): array
    {
        return [
            'car_trip_id.required' => 'Car trip ID is required.',
            'car_trip_id.integer' => 'Car trip ID must be an integer.',
            'car_trip_id.exists' => 'Car trip does not exist.',
        ];
    }
}
