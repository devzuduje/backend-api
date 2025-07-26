<?php

namespace App\Http\Requests\V1\HotelRoom;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHotelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'integer', 'exists:hotels,id'],
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'accommodation_id' => ['required', 'integer', 'exists:accommodations,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'unique_combination' => [
                Rule::unique('hotel_rooms')
                    ->where('hotel_id', $this->input('hotel_id'))
                    ->where('room_type_id', $this->input('room_type_id'))
                    ->where('accommodation_id', $this->input('accommodation_id'))
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hotelId = $this->input('hotel_id');
            $roomTypeId = $this->input('room_type_id');
            $accommodationId = $this->input('accommodation_id');

            if ($hotelId && $roomTypeId && $accommodationId) {
                $exists = \App\Models\HotelRoom::query()->where('hotel_id', $hotelId)
                    ->where('room_type_id', $roomTypeId)
                    ->where('accommodation_id', $accommodationId)
                    ->whereNull('deleted_at')
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('combination', 'Ya existe una habitación con esta combinación de hotel, tipo de habitación y acomodación.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => 'El ID del hotel es obligatorio.',
            'hotel_id.integer' => 'El ID del hotel debe ser un número entero.',
            'hotel_id.exists' => 'El hotel especificado no existe.',
            'room_type_id.required' => 'El ID del tipo de habitación es obligatorio.',
            'room_type_id.integer' => 'El ID del tipo de habitación debe ser un número entero.',
            'room_type_id.exists' => 'El tipo de habitación especificado no existe.',
            'accommodation_id.required' => 'El ID de la acomodación es obligatorio.',
            'accommodation_id.integer' => 'El ID de la acomodación debe ser un número entero.',
            'accommodation_id.exists' => 'La acomodación especificada no existe.',
            'quantity.required' => 'La cantidad de habitaciones es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'quantity.max' => 'La cantidad no puede ser mayor a 1000.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'hotel_id' => [
                'description' => 'ID del hotel.',
                'example' => 1,
            ],
            'room_type_id' => [
                'description' => 'ID del tipo de habitación.',
                'example' => 2,
            ],
            'accommodation_id' => [
                'description' => 'ID de la acomodación.',
                'example' => 1,
            ],
            'quantity' => [
                'description' => 'Cantidad de habitaciones disponibles.',
                'example' => 10,
            ],
        ];
    }
}
