<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

final class RoomTypeBuilder extends Builder
{
    public function byCode(string $code): self
    {
        return $this->where(column: 'code', operator: '=', value: $code);
    }

    public function byName(string $name): self
    {
        return $this->where(column: 'name', operator: '=', value: $name);
    }

    public function withTrashed(): self
    {
        return $this->withoutGlobalScope(\Illuminate\Database\Eloquent\SoftDeletingScope::class);
    }

    public function onlyTrashed(): self
    {
        return $this->withoutGlobalScope(\Illuminate\Database\Eloquent\SoftDeletingScope::class)
                   ->whereNotNull('deleted_at');
    }
}
