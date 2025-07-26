<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

final class HotelRoomBuilder extends Builder
{
    public function byHotel(int $hotelId): self
    {
        return $this->where(column: 'hotel_id', operator: '=', value: $hotelId);
    }

    public function byRoomType(int $roomTypeId): self
    {
        return $this->where(column: 'room_type_id', operator: '=', value: $roomTypeId);
    }

    public function byAccommodation(int $accommodationId): self
    {
        return $this->where(column: 'accommodation_id', operator: '=', value: $accommodationId);
    }

    public function withMinQuantity(int $minQuantity): self
    {
        return $this->where(column: 'quantity', operator: '>=', value: $minQuantity);
    }

    public function withTrashed(): self
    {
        return $this->withoutGlobalScope(\Illuminate\Database\Eloquent\SoftDeletingScope::class);
    }

    public function onlyTrashed(): self
    {
        return $this->withoutGlobalScope(\Illuminate\Database\Eloquent\SoftDeletingScope::class)
            ->whereNotNull('deleted_at');
    }
}
