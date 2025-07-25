<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    protected function casts(): array
    {
        return [];
    }
}