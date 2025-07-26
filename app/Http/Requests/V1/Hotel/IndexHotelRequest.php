<?php

namespace App\Http\Requests\V1\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class IndexHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city' => ['sometimes', 'string', 'max:255'],
            'min_rooms' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'with_trashed' => ['sometimes', 'boolean'],
            'only_trashed' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'city.max' => 'El filtro de ciudad no puede exceder 255 caracteres.',
            'min_rooms.integer' => 'El filtro de habitaciones mínimas debe ser un número entero.',
            'min_rooms.min' => 'El filtro de habitaciones mínimas debe ser al menos 1.',
            'search.max' => 'El término de búsqueda no puede exceder 255 caracteres.',
            'page.integer' => 'El número de página debe ser un entero.',
            'page.min' => 'El número de página debe ser al menos 1.',
            'per_page.integer' => 'El número de elementos por página debe ser un entero.',
            'per_page.min' => 'Debe mostrar al menos 1 elemento por página.',
            'per_page.max' => 'No se pueden mostrar más de 100 elementos por página.',
            'with_trashed.boolean' => 'El parámetro with_trashed debe ser verdadero o falso.',
            'only_trashed.boolean' => 'El parámetro only_trashed debe ser verdadero o falso.',
        ];
    }

    public function queryParameters(): array
    {
        return [
            'city' => [
                'description' => 'Filtrar por ciudad',
                'example' => 'Bogotá',
            ],
            'min_rooms' => [
                'description' => 'Filtrar por número mínimo de habitaciones',
                'example' => 50,
            ],
            'search' => [
                'description' => 'Buscar por nombre o NIT del hotel',
                'example' => 'Plaza',
            ],
            'page' => [
                'description' => 'Número de página para paginación',
                'example' => 1,
            ],
            'per_page' => [
                'description' => 'Elementos por página (máximo 100)',
                'example' => 15,
            ],
            'with_trashed' => [
                'description' => 'Incluir hoteles eliminados (borrado lógico)',
                'example' => false,
            ],
            'only_trashed' => [
                'description' => 'Mostrar solo hoteles eliminados',
                'example' => false,
            ],
        ];
    }
}
