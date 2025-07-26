<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;

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
            'deleted_at' => 'datetime',
        ];
    }
}
