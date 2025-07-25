<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password_confirmation.required' => 'La confirmación de contraseña es obligatoria.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Nombre completo del usuario',
                'example' => 'Juan Pérez',
            ],
            'email' => [
                'description' => 'Dirección de correo electrónico única del usuario',
                'example' => 'juan.perez@example.com',
            ],
            'password' => [
                'description' => 'Contraseña del usuario (mínimo 8 caracteres)',
                'example' => 'password123',
            ],
            'password_confirmation' => [
                'description' => 'Confirmación de la contraseña (debe coincidir con password)',
                'example' => 'password123',
            ],
        ];
    }
}
