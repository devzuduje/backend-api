<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\RegisterRequest;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Autenticación')]
#[Subgroup('Gestión de Usuarios')]
class RegisterController extends Controller
{
    #[Endpoint(
        title: 'Registrar nuevo usuario',
        description: 'Crea una nueva cuenta de usuario en el sistema y devuelve un token de acceso para autenticación inmediata.'
    )]
    public function __invoke(RegisterRequest $request, RegisterUserAction $action)
    {
        $result = $action($request->validated());

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => data_get($result, 'user'),
            'token' => data_get($result, 'token'),
        ], 201);
    }
}
