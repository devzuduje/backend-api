<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Usuario')]
#[Subgroup('Información del Usuario')]
class UserController extends Controller
{
    #[Endpoint(
        title: 'Obtener información del usuario',
        description: 'Devuelve la información completa del usuario autenticado actualmente.'
    )]
    #[Authenticated]
    public function __invoke(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
