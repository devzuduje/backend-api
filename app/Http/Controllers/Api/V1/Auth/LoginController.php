<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\LoginUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Autenticación')]
#[Subgroup('Sesión de Usuario')]
class LoginController extends Controller
{
    #[Endpoint(
        title: 'Iniciar sesión',
        description: 'Autentica al usuario con email y contraseña, devolviendo un token de acceso válido para realizar peticiones autenticadas.'
    )]
    public function __invoke(LoginRequest $request, LoginUserAction $action)
    {
        $result = $action($request->validated());

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => data_get($result, 'user'),
            'token' => data_get($result, 'token'),
        ]);
    }
}
