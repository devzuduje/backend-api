<?php

namespace App\Http\Requests\V1\Hotel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:500'],
            'city' => ['sometimes', 'string', 'max:255'],
            'nit' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('hotels', 'nit')->ignore($this->route('hotel')),
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('hotels', 'email')->ignore($this->route('hotel')),
            ],
            'phone' => ['sometimes', 'string', 'max:20'],
            'max_rooms' => ['sometimes', 'integer', 'min:1', 'max:10000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('name', function ($input) {
            $hotelId = $this->route('hotel');
            $city = $this->input('city') ?? $this->getOriginalCity();
            
            $rule = Rule::unique('hotels', 'name')->ignore($hotelId);
            
            if ($city) {
                $rule->where('city', $city);
            }
            
            return $rule;
        }, function ($input) {
            return $input->name;
        });
    }

    private function getOriginalCity(): ?string
    {
        $hotelId = $this->route('hotel');
        if ($hotelId) {
            $hotel = \App\Models\Hotel::find($hotelId);
            return $hotel?->city;
        }
        return null;
    }

    public function messages(): array
    {
        return [
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'name.unique' => 'Ya existe un hotel con este nombre en la misma ciudad.',
            'address.max' => 'La dirección no puede exceder 500 caracteres.',
            'city.max' => 'La ciudad no puede exceder 255 caracteres.',
            'nit.unique' => 'Ya existe un hotel con este NIT.',
            'nit.max' => 'El NIT no puede exceder 20 caracteres.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Ya existe un hotel con este email.',
            'email.max' => 'El email no puede exceder 255 caracteres.',
            'phone.max' => 'El teléfono no puede exceder 20 caracteres.',
            'max_rooms.integer' => 'El número máximo de habitaciones debe ser un número entero.',
            'max_rooms.min' => 'El hotel debe tener al menos 1 habitación.',
            'max_rooms.max' => 'El número máximo de habitaciones no puede exceder 10,000.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Nombre del hotel (opcional)',
                'example' => 'Hotel Plaza Mayor Renovado',
            ],
            'address' => [
                'description' => 'Dirección completa del hotel (opcional)',
                'example' => 'Calle 10 # 15-25, Centro Histórico',
            ],
            'city' => [
                'description' => 'Ciudad donde se ubica el hotel (opcional)',
                'example' => 'Medellín',
            ],
            'nit' => [
                'description' => 'Número de identificación tributaria del hotel (opcional)',
                'example' => '900123456-2',
            ],
            'email' => [
                'description' => 'Email de contacto del hotel (opcional)',
                'example' => 'nuevo@hotelplazamayor.com',
            ],
            'phone' => [
                'description' => 'Teléfono de contacto del hotel (opcional)',
                'example' => '+57 4 567 8900',
            ],
            'max_rooms' => [
                'description' => 'Número máximo de habitaciones del hotel (opcional)',
                'example' => 200,
            ],
        ];
    }
}
