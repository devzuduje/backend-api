<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'address',
        'city',
        'nit',
        'max_rooms',
    ];

    protected function casts(): array
    {
        return [
            'max_rooms' => 'integer',
        ];
    }
}