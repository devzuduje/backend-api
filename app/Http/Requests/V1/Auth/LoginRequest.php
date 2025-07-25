<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Dirección de correo electrónico del usuario registrado',
                'example' => 'juan.perez@example.com',
            ],
            'password' => [
                'description' => 'Contraseña del usuario',
                'example' => 'password123',
            ],
        ];
    }
}
