<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\Auth;

class UserLogoutService
{
    function logout()
    {
        $user = Auth::user();

        $user->tokens->each(function ($token) {
            $token->delete();
        });
    }
}
