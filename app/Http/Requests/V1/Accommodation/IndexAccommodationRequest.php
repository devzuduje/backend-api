<?php

namespace App\Http\Requests\V1\Accommodation;

use Illuminate\Foundation\Http\FormRequest;

class IndexAccommodationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string', 'max:255'],
            'min_capacity' => ['sometimes', 'integer', 'min:1'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'with_trashed' => ['sometimes', 'boolean'],
            'only_trashed' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'search.string' => 'El campo de búsqueda debe ser una cadena de texto.',
            'search.max' => 'El campo de búsqueda no puede tener más de 255 caracteres.',
            'min_capacity.integer' => 'La capacidad mínima debe ser un número entero.',
            'min_capacity.min' => 'La capacidad mínima debe ser al menos 1.',
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
            'search' => [
                'description' => 'Buscar por nombre o código de la acomodación.',
                'example' => 'Sencilla',
            ],
            'min_capacity' => [
                'description' => 'Filtrar por capacidad mínima de personas.',
                'example' => 2,
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
                'description' => 'Incluir acomodaciones eliminadas (borrado lógico).',
                'example' => false,
            ],
            'only_trashed' => [
                'description' => 'Mostrar solo acomodaciones eliminadas.',
                'example' => false,
            ],
        ];
    }
}
