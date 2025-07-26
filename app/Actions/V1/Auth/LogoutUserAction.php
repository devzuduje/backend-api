<?php

namespace App\Actions\V1\Auth;

use Illuminate\Http\Request;

class LogoutUserAction
{
    public function __invoke(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
