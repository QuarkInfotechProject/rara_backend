<?php

namespace Modules\User\Service;

use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;

class UserSocialLoginRedirectService
{
//    function handleSocialLoginRedirect(string $provider)
//    {
//        if ($provider == 'google') {
//            return Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
//        } else {
//            throw new Exception('Social Provider not found.', ErrorCode::BAD_REQUEST);
//        }
//    }

    public function handleSocialLoginRedirect(string $provider, ?string $returnUrl = null)
    {
        if ($provider !== 'google') {
            throw new Exception('Social Provider not found.', ErrorCode::BAD_REQUEST);
        }

        $state = base64_encode(json_encode([
            'returnUrl' => $returnUrl,
            'nonce' => Str::random(32),
            'timestamp' => time()
        ]));

        return Socialite::driver('google')
            ->stateless()
            ->with(['state' => $state])
            ->redirect()
            ->getTargetUrl();
    }
}
