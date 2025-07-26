<?php

namespace App\Actions\V1\Auth;

use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function __invoke(array $data): array
    {
        /** @var \App\Models\User $user */
        $user = \App\Models\User::query()->create([
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'password' => Hash::make(data_get($data, 'password')),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $result = [];
        data_set($result, 'user', $user);
        data_set($result, 'token', $token);

        return $result;
    }
}
