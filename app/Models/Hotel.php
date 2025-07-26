<?php

namespace App\Models;

use App\Models\Builders\HotelBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Hotel extends Base\Hotel
{
    public function newEloquentBuilder($query): HotelBuilder
    {
        return new HotelBuilder($query);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtoupper($value),
        );
    }

    public function nit(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            set: fn (string $value) => trim($value),
        );
    }

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'hotel_rooms')
            ->withPivot('accommodation_id', 'quantity')
            ->withTimestamps();
    }

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'hotel_rooms')
            ->withPivot('room_type_id', 'quantity')
            ->withTimestamps();
    }
}
