<?php

namespace Modules\User\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\UserBaseController;
use Modules\User\Service\UserSocialLoginRedirectService;

class UserSocialLoginRedirectController extends UserBaseController
{
    public function __construct(private UserSocialLoginRedirectService $userSocialLoginRedirectService)
    {
    }

    public function __invoke(string $provider, Request $request)
    {
        $returnUrl = $request->query('returnUrl');

        $url = $this->userSocialLoginRedirectService->handleSocialLoginRedirect($provider, $returnUrl);

        return $this->successResponse('Redirect successful.', $url);
    }
}
