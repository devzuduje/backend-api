<?php

namespace App\Http\Requests\V1\RoomType;

use Illuminate\Foundation\Http\FormRequest;

class IndexRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
            'search' => [
                'description' => 'Buscar por nombre o código del tipo de habitación',
                'example' => 'Suite',
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
                'description' => 'Incluir tipos de habitación eliminados (borrado lógico)',
                'example' => false,
            ],
            'only_trashed' => [
                'description' => 'Mostrar solo tipos de habitación eliminados',
                'example' => false,
            ],
        ];
    }
}
