<?php

namespace App\Http\Requests\V1\HotelRoom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'quantity.max' => 'La cantidad no puede ser mayor a 1000.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'quantity' => [
                'description' => 'Cantidad de habitaciones disponibles (opcional).',
                'example' => 15,
            ],
        ];
    }
}
