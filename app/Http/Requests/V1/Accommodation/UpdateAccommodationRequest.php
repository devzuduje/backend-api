<?php

namespace App\Http\Requests\V1\Accommodation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccommodationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $accommodationId = $this->route('accommodation')?->id;

        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('accommodations', 'name')->ignore($accommodationId)],
            'code' => ['sometimes', 'string', 'max:10', Rule::unique('accommodations', 'code')->ignore($accommodationId)],
            'capacity' => ['sometimes', 'integer', 'min:1', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.unique' => 'Ya existe una acomodación con este nombre.',
            'code.string' => 'El código debe ser una cadena de texto.',
            'code.max' => 'El código no puede tener más de 10 caracteres.',
            'code.unique' => 'Ya existe una acomodación con este código.',
            'capacity.integer' => 'La capacidad debe ser un número entero.',
            'capacity.min' => 'La capacidad debe ser al menos 1 persona.',
            'capacity.max' => 'La capacidad no puede ser mayor a 20 personas.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Nombre de la acomodación (opcional).',
                'example' => 'Acomodación Doble Premium',
            ],
            'code' => [
                'description' => 'Código único de la acomodación (opcional).',
                'example' => 'DOBLE_PREM',
            ],
            'capacity' => [
                'description' => 'Capacidad máxima de personas (opcional).',
                'example' => 4,
            ],
        ];
    }
}
