<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'accommodation_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'hotel_id' => 'integer',
            'room_type_id' => 'integer',
            'accommodation_id' => 'integer',
            'quantity' => 'integer',
            'deleted_at' => 'datetime',
        ];
    }
}
