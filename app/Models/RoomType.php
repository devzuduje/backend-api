<?php

namespace App\Models;

use App\Models\Builders\RoomTypeBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RoomType extends Base\RoomType
{
    public function newEloquentBuilder($query): RoomTypeBuilder
    {
        return new RoomTypeBuilder($query);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    public function code(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtoupper($value),
        );
    }

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_rooms')
                    ->withPivot('accommodation_id', 'quantity')
                    ->withTimestamps();
    }

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'hotel_rooms')
                    ->withPivot('hotel_id', 'quantity')
                    ->withTimestamps();
    }
}
