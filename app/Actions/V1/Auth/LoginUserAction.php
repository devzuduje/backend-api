<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class LoginUserAction
{
    public function __invoke(array $credentials): array
    {
        $email = data_get($credentials, 'email');
        $password = data_get($credentials, 'password');

        /** @var User|null $user */
        $user = User::byEmail($email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Las credenciales proporcionadas son incorrectas.',
                ], 401)
            );
        }

        $token = $user->createToken('api-token')->plainTextToken;

        $result = [];
        data_set($result, 'user', $user);
        data_set($result, 'token', $token);

        return $result;
    }
}
