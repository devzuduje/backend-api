<?php

namespace App\Http\Requests\V1\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:255'],
            'nit' => ['required', 'string', 'max:20', 'unique:hotels,nit'],
            'max_rooms' => ['required', 'integer', 'min:1', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del hotel es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'address.required' => 'La dirección es obligatoria.',
            'address.max' => 'La dirección no puede exceder 500 caracteres.',
            'city.required' => 'La ciudad es obligatoria.',
            'city.max' => 'La ciudad no puede exceder 255 caracteres.',
            'nit.required' => 'El NIT es obligatorio.',
            'nit.unique' => 'Ya existe un hotel con este NIT.',
            'nit.max' => 'El NIT no puede exceder 20 caracteres.',
            'max_rooms.required' => 'El número máximo de habitaciones es obligatorio.',
            'max_rooms.integer' => 'El número máximo de habitaciones debe ser un número entero.',
            'max_rooms.min' => 'El hotel debe tener al menos 1 habitación.',
            'max_rooms.max' => 'El número máximo de habitaciones no puede exceder 10,000.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Nombre del hotel',
                'example' => 'Hotel Plaza Mayor',
            ],
            'address' => [
                'description' => 'Dirección completa del hotel',
                'example' => 'Calle 10 # 15-20, Centro Histórico',
            ],
            'city' => [
                'description' => 'Ciudad donde se ubica el hotel',
                'example' => 'Bogotá',
            ],
            'nit' => [
                'description' => 'Número de identificación tributaria del hotel',
                'example' => '900123456-1',
            ],
            'max_rooms' => [
                'description' => 'Número máximo de habitaciones del hotel',
                'example' => 150,
            ],
        ];
    }
}
