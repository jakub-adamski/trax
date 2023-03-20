<?php

namespace App\Http\Requests\Api\Car;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property integer car_id
 * @property float miles
 * @property string date
 */
class CarTripCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => 'required|exists:cars,id',
            'miles' => 'required|numeric',
            'date' => 'required|date',
        ];
    }

    /**
     * @todo translations should be moved to the lang folder - this is just example
     */
    public function messages(): array
    {
        return [
            'car_id.required' => 'Car ID is required.',
            'car_id.exists' => 'Car ID does not exist in the database.',
            'miles.required' => 'Trip miles are required.',
            'miles.numeric' => 'Trip miles must be a number.',
            'date.required' => 'Car trip date is required.',
            'date.date_format' => 'Car trip date must be a valid date format.',
            'date.before_or_equal' => 'Car trip date must be before or equal to the current day.',
        ];
    }
}
