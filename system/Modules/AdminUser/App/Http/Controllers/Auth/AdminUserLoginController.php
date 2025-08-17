<?php

namespace Modules\AdminUser\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Modules\AdminUser\Service\Auth\AdminUserLoginService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class AdminUserLoginController extends AdminBaseController
{
    function __construct(private AdminUserLoginService $adminUserLoginService)
    {
    }

    function __invoke(Request $request)
    {
        $token = $this->adminUserLoginService->login($request);

        return $this->successResponse('Admin has been logged in successfully.', $token);
    }
}
