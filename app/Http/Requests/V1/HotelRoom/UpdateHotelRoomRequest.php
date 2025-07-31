<?php

namespace App\Http\Requests\V1\HotelRoom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $newQuantity = $this->input('quantity');
            
            if ($newQuantity) {
                $hotelRoomId = $this->route('hotel_room'); // ID de la habitación que se está actualizando
                $hotelRoom = \App\Models\HotelRoom::find($hotelRoomId);
                
                if ($hotelRoom) {
                    $hotel = $hotelRoom->hotel;
                    
                    // Obtener total actual excluyendo la habitación que se está actualizando
                    $currentTotal = \App\Models\HotelRoom::where('hotel_id', $hotel->id)
                                                        ->where('id', '!=', $hotelRoomId)
                                                        ->whereNull('deleted_at')
                                                        ->sum('quantity');
                    
                    $newTotal = $currentTotal + $newQuantity;
                    
                    if ($newTotal > $hotel->max_rooms) {
                        $available = $hotel->max_rooms - $currentTotal;
                        $validator->errors()->add('quantity', 
                            "No se puede actualizar a {$newQuantity} habitaciones. " .
                            "El hotel permite máximo {$hotel->max_rooms} habitaciones. " .
                            "Ya hay {$currentTotal} registradas (excluyendo esta). Disponibles: {$available}."
                        );
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'quantity.max' => 'La cantidad no puede ser mayor a 1000.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'quantity' => [
                'description' => 'Cantidad de habitaciones disponibles (opcional).',
                'example' => 15,
            ],
        ];
    }
}
