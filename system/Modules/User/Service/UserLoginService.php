<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;
use Modules\User\App\Models\User;

class UserLoginService
{
    function login($request)
    {
        $this->checkTooManyFailedAttempts();

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))
            ->where('status', User::STATUS_ACTIVE)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($this->throttleKey(), $seconds = 18);
            throw new Exception('Email & Password do not match our records.', ErrorCode::UNAUTHORIZED);
        }

        if ($user->status == User::STATUS_BLOCKED) {
            throw new Exception('Your account is currently blocked. Please contact support for assistance.', ErrorCode::FORBIDDEN);
        }

        RateLimiter::clear($this->throttleKey());

        $token = $user->createToken($request['email'])->accessToken;

        return [
            'token' => $token,
            'user' => [
                'name' => $user->full_name,
            ],
        ];
    }

    private function throttleKey()
    {
        return Str::lower(request('email'));
    }

    private function checkTooManyFailedAttempts()
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 50)) {
            throw new Exception('Too many login attempts.', ErrorCode::TOO_MANY_ATTEMPTS);
        }
    }
}
