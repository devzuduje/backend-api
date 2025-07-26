<?php

namespace App\Http\Requests\V1\HotelRoom;

use Illuminate\Foundation\Http\FormRequest;

class IndexHotelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['sometimes', 'integer', 'exists:hotels,id'],
            'room_type_id' => ['sometimes', 'integer', 'exists:room_types,id'],
            'accommodation_id' => ['sometimes', 'integer', 'exists:accommodations,id'],
            'min_quantity' => ['sometimes', 'integer', 'min:1'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'with_trashed' => ['sometimes', 'boolean'],
            'only_trashed' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.integer' => 'El ID del hotel debe ser un número entero.',
            'hotel_id.exists' => 'El hotel especificado no existe.',
            'room_type_id.integer' => 'El ID del tipo de habitación debe ser un número entero.',
            'room_type_id.exists' => 'El tipo de habitación especificado no existe.',
            'accommodation_id.integer' => 'El ID de la acomodación debe ser un número entero.',
            'accommodation_id.exists' => 'La acomodación especificada no existe.',
            'min_quantity.integer' => 'La cantidad mínima debe ser un número entero.',
            'min_quantity.min' => 'La cantidad mínima debe ser al menos 1.',
            'page.integer' => 'El número de página debe ser un entero.',
            'page.min' => 'El número de página debe ser al menos 1.',
            'per_page.integer' => 'Los elementos por página deben ser un entero.',
            'per_page.min' => 'Los elementos por página deben ser al menos 1.',
            'per_page.max' => 'Los elementos por página no pueden ser más de 100.',
            'with_trashed.boolean' => 'El parámetro with_trashed debe ser verdadero o falso.',
            'only_trashed.boolean' => 'El parámetro only_trashed debe ser verdadero o falso.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'hotel_id' => [
                'description' => 'Filtrar por ID del hotel.',
                'example' => 1,
            ],
            'room_type_id' => [
                'description' => 'Filtrar por ID del tipo de habitación.',
                'example' => 2,
            ],
            'accommodation_id' => [
                'description' => 'Filtrar por ID de la acomodación.',
                'example' => 1,
            ],
            'min_quantity' => [
                'description' => 'Filtrar por cantidad mínima de habitaciones.',
                'example' => 5,
            ],
            'page' => [
                'description' => 'Número de página para paginación.',
                'example' => 1,
            ],
            'per_page' => [
                'description' => 'Elementos por página (máximo 100).',
                'example' => 15,
            ],
            'with_trashed' => [
                'description' => 'Incluir habitaciones eliminadas (borrado lógico).',
                'example' => false,
            ],
            'only_trashed' => [
                'description' => 'Mostrar solo habitaciones eliminadas.',
                'example' => false,
            ],
        ];
    }
}
