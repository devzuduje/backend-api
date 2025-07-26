<?php

namespace App\Http\Requests\V1\RoomType;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:room_types,code'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del tipo de habitación es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'code.required' => 'El código del tipo de habitación es obligatorio.',
            'code.max' => 'El código no puede exceder 10 caracteres.',
            'code.unique' => 'Ya existe un tipo de habitación con este código.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Nombre del tipo de habitación',
                'example' => 'Suite Presidencial',
            ],
            'code' => [
                'description' => 'Código único del tipo de habitación',
                'example' => 'SUITE_PRES',
            ],
        ];
    }
}
