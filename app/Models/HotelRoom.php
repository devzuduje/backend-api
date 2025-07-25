<?php

namespace App\Models;

use App\Models\Builders\HotelRoomBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HotelRoom extends Base\HotelRoom
{
    public function newEloquentBuilder($query): HotelRoomBuilder
    {
        return new HotelRoomBuilder($query);
    }

    public function quantity(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => $value,
            set: fn (int $value) => max(0, $value),
        );
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }
}
