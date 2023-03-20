<?php

namespace App\Http\Requests\Api\Car;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int car_id
 */
class CarDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['car_id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'car_id' => 'required|integer|exists:cars,id',
        ];
    }

    /**
     * @todo translations should be moved to the lang folder - this is just example
     */
    public function messages(): array
    {
        return [
            'car_id.required' => 'Car ID is required.',
            'car_id.integer' => 'Car ID must be an integer.',
            'car_id.exists' => 'Car does not exist.',
        ];
    }
}
