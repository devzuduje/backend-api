<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

final class AccommodationBuilder extends Builder
{
    public function byCode(string $code): self
    {
        return $this->where(column: 'code', operator: '=', value: $code);
    }

    public function byName(string $name): self
    {
        return $this->where(column: 'name', operator: '=', value: $name);
    }

    public function byCapacity(int $capacity): self
    {
        return $this->where(column: 'capacity', operator: '=', value: $capacity);
    }

    public function withMinCapacity(int $minCapacity): self
    {
        return $this->where(column: 'capacity', operator: '>=', value: $minCapacity);
    }
}
