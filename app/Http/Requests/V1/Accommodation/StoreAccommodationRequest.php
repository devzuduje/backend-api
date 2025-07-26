<?php

namespace App\Http\Requests\V1\Accommodation;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccommodationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:accommodations,name'],
            'code' => ['required', 'string', 'max:10', 'unique:accommodations,code'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la acomodación es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.unique' => 'Ya existe una acomodación con este nombre.',
            'code.required' => 'El código de la acomodación es obligatorio.',
            'code.string' => 'El código debe ser una cadena de texto.',
            'code.max' => 'El código no puede tener más de 10 caracteres.',
            'code.unique' => 'Ya existe una acomodación con este código.',
            'capacity.required' => 'La capacidad de la acomodación es obligatoria.',
            'capacity.integer' => 'La capacidad debe ser un número entero.',
            'capacity.min' => 'La capacidad debe ser al menos 1 persona.',
            'capacity.max' => 'La capacidad no puede ser mayor a 20 personas.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Nombre de la acomodación.',
                'example' => 'Acomodación Sencilla',
            ],
            'code' => [
                'description' => 'Código único de la acomodación.',
                'example' => 'SIMPLE',
            ],
            'capacity' => [
                'description' => 'Capacidad máxima de personas.',
                'example' => 2,
            ],
        ];
    }
}
