<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

final class UserBuilder extends Builder
{
    public function byEmail(string $email): self
    {
        return $this->where(column: 'email', operator: '=', value: $email);
    }

    public function active(): self
    {
        return $this->whereNotNull('email_verified_at');
    }

    public function inactive(): self
    {
        return $this->whereNull('email_verified_at');
    }
}
