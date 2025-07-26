<?php

namespace App\Http\Requests\V1\RoomType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => [
                'sometimes',
                'string',
                'max:10',
                Rule::unique('room_types', 'code')->ignore($this->route('room_type')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'code.max' => 'El código no puede exceder 10 caracteres.',
            'code.unique' => 'Ya existe un tipo de habitación con este código.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Nombre del tipo de habitación (opcional)',
                'example' => 'Suite Presidencial Premium',
            ],
            'code' => [
                'description' => 'Código único del tipo de habitación (opcional)',
                'example' => 'SUITE_PREM',
            ],
        ];
    }
}
