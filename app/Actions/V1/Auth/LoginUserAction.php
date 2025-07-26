<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginUserAction
{
    public function __invoke(array $credentials): array
    {
        $email = data_get($credentials, 'email');
        $password = data_get($credentials, 'password');

        /** @var User|null $user */
        $user = User::byEmail($email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        $result = [];
        data_set($result, 'user', $user);
        data_set($result, 'token', $token);

        return $result;
    }
}
