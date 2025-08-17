<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;
use Modules\SystemConfiguration\App\Models\SystemConfig;
use Modules\User\App\Models\User;
use Modules\User\App\Models\VerificationCode;
use Modules\User\DTO\UserRegisterDTO;

class UserRegisterService
{
    function register(UserRegisterDTO $userRegisterDTO)
    {
        $verification = VerificationCode::where('email', $userRegisterDTO->email)
            ->latest()
            ->first();

        if (!$verification) {
            throw new Exception('Email not found.', ErrorCode::NOT_FOUND);
        }

        $codeAttempts = SystemConfig::firstWhere('name', 'code_attempts')->value('value');

        if ($verification->attempts >= $codeAttempts) {
            throw new Exception('Too many verification code attempts.', ErrorCode::TOO_MANY_REQUESTS);
        }

        if ($verification->expires_at < now()) {
            throw new Exception('Verification code has already expired!', ErrorCode::GONE);
        }

        if ($verification->code != $userRegisterDTO->verificationCode) {
            $verification->attempts++;
            $verification->save();
            throw new Exception('Verification code doesn\'t match.', ErrorCode::BAD_REQUEST);
        }

        return DB::transaction(function () use ($userRegisterDTO, $verification) {
            $user = User::create([
                'uuid' => Str::uuid()->toString(),
                'email' => $userRegisterDTO->email,
                'full_name' => $userRegisterDTO->fullName,
                'password' => bcrypt($userRegisterDTO->password),
                'status' => User::STATUS_ACTIVE,
            ]);

            $verification->delete();

            $token =  $user->createToken($userRegisterDTO->email)->accessToken;

            return [
                'token' => $token,
                'user' => [
                    'name' => $userRegisterDTO->fullName,
                ],
            ];
        });
    }
}
