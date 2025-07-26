<?php

namespace App\Models;

use App\Models\Builders\AccommodationBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Accommodation extends Base\Accommodation
{
    public function newEloquentBuilder($query): AccommodationBuilder
    {
        return new AccommodationBuilder($query);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst((string) $value),
            set: fn ($value) => strtolower(trim((string) $value))
        );
    }

    public function code(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper((string) $value),
            set: fn ($value) => strtoupper(trim((string) $value))
        );
    }

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_rooms')
            ->withPivot('room_type_id', 'quantity')
            ->withTimestamps();
    }

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'hotel_rooms')
            ->withPivot('hotel_id', 'quantity')
            ->withTimestamps();
    }
}
