<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

final class HotelBuilder extends Builder
{
    public function byCity(string $city): self
    {
        return $this->where(column: 'city', operator: '=', value: $city);
    }

    public function byNit(string $nit): self
    {
        return $this->where(column: 'nit', operator: '=', value: $nit);
    }

    public function withMinRooms(int $minRooms): self
    {
        return $this->where(column: 'max_rooms', operator: '>=', value: $minRooms);
    }
}