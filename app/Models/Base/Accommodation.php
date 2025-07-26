<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'capacity',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'deleted_at' => 'datetime',
        ];
    }
}
