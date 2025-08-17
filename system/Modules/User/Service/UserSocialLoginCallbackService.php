<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;
use Modules\User\App\Models\User;

class UserSocialLoginCallbackService
{
    public function handleSocialLoginCallback(string $provider)
    {
        try {
            $this->validateProvider($provider);

            $socialUser = Socialite::driver($provider)->stateless()->user();

            $user = $this->findOrCreateUser($socialUser, $provider);

            Auth::login($user);

            $token = $user->createToken($user->email)->accessToken;

            return [
                'token' => $token,
                'user' => [
                    'name' => $user->full_name,
                ],
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function validateProvider(string $provider)
    {
        if ($provider !== 'google') {
            throw new Exception('Social Provider not found.', ErrorCode::BAD_REQUEST);
        }
    }

    private function findOrCreateUser($socialUser, string $provider): User
    {
        $user = User::where('oauth_id', $socialUser->id)
            ->where('oauth_type', $provider)
            ->first();

        if ($user) {
            if ($user->status !== User::STATUS_ACTIVE) {
                throw new \Exception('User account is not active.', ErrorCode::FORBIDDEN);
            }
        } else {
            DB::beginTransaction();

            try {
                $user = $this->createUser($socialUser, $provider);
                DB::commit();
            } catch (\Exception $exception) {

                DB::rollBack();
                throw $exception;
            }
        }

        return $user;
    }

    private function createUser($socialUser, string $provider): User
    {
        try {
            return User::create([
                'uuid' => Str::uuid(),
                'email' => $socialUser->email,
                'full_name' => $socialUser->name,
                'profile_picture' => $socialUser->avatar,
                'password' => null,
                'oauth_type' => $provider,
                'oauth_id' => $socialUser->id,
                'status' => User::STATUS_ACTIVE,
            ]);
        } catch (\Exception $exception) {
            Log::error('Error creating user record: ' . $exception->getMessage(), [
                'exception' => $exception,
                'socialUser' => $socialUser,
                'provider' => $provider
            ]);
            if ($exception->getCode() == 23000) {
                throw new Exception('An address for this user already exists.', ErrorCode::UNPROCESSABLE_CONTENT);
                };
            throw $exception;
        }
    }
}
