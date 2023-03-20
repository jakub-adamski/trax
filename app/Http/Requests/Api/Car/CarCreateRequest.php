<?php

namespace App\Http\Requests\Api\Car;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string make
 * @property string model
 * @property integer year
 */
class CarCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'make' => 'required|string|min:1|max:50',
            'model' => 'required|string|min:1|max:100',
            'year' => 'required|integer|min:1900|max:'.date('Y'),
        ];
    }

    /**
     * @todo translations should be moved to the lang folder - this is just example
     */
    public function messages(): array
    {
        return [
            'make.required' => 'Car make is required.',
            'make.min' => 'Car make must be at least :min characters.',
            'make.max' => 'Car make may not be greater than :max characters.',
            'model.required' => 'Car model is required.',
            'model.min' => 'Car model must be at least :min characters.',
            'model.max' => 'Car model may not be greater than :max characters.',
            'year.required' => 'Year of manufacture of the car is required.',
            'year.min' => 'Year of manufacture of the car must be greater than :min.',
            'year.max' => 'Year of manufacture of the car cannot be greater than :max.'
        ];
    }
}
