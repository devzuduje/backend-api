<?php

namespace App\Http\Requests\V1\Hotel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:500'],
            'city' => ['sometimes', 'string', 'max:255'],
            'nit' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('hotels', 'nit')->ignore($this->route('hotel')),
            ],
            'max_rooms' => ['sometimes', 'integer', 'min:1', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'address.max' => 'La dirección no puede exceder 500 caracteres.',
            'city.max' => 'La ciudad no puede exceder 255 caracteres.',
            'nit.unique' => 'Ya existe un hotel con este NIT.',
            'nit.max' => 'El NIT no puede exceder 20 caracteres.',
            'max_rooms.integer' => 'El número máximo de habitaciones debe ser un número entero.',
            'max_rooms.min' => 'El hotel debe tener al menos 1 habitación.',
            'max_rooms.max' => 'El número máximo de habitaciones no puede exceder 10,000.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Nombre del hotel (opcional)',
                'example' => 'Hotel Plaza Mayor Renovado',
            ],
            'address' => [
                'description' => 'Dirección completa del hotel (opcional)',
                'example' => 'Calle 10 # 15-25, Centro Histórico',
            ],
            'city' => [
                'description' => 'Ciudad donde se ubica el hotel (opcional)',
                'example' => 'Medellín',
            ],
            'nit' => [
                'description' => 'Número de identificación tributaria del hotel (opcional)',
                'example' => '900123456-2',
            ],
            'max_rooms' => [
                'description' => 'Número máximo de habitaciones del hotel (opcional)',
                'example' => 200,
            ],
        ];
    }
}
