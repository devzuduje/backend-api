<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function __invoke(array $data): array
    {
        $user = User::create([
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