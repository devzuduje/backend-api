<?php

namespace App\Models;

use App\Models\Builders\UserBuilder;

class User extends Base\User
{
    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }
}
