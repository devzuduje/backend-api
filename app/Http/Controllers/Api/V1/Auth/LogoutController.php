<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\LogoutUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Autenticación')]
#[Subgroup('Sesión de Usuario')]
class LogoutController extends Controller
{
    #[Endpoint(
        title: 'Cerrar sesión',
        description: 'Invalida el token de acceso actual del usuario autenticado, cerrando su sesión de forma segura.'
    )]
    #[Authenticated]
    public function __invoke(Request $request, LogoutUserAction $action)
    {
        $action($request);

        return response()->json([
            'message' => 'Cierre de sesión exitoso',
        ]);
    }
}
