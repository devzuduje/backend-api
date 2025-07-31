<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'city',
        'nit',
        'email',
        'phone',
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
