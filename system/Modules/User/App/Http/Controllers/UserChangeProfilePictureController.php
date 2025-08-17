<?php

namespace Modules\User\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\UserBaseController;
use Modules\User\Service\UserChangeProfilePictureService;

class UserChangeProfilePictureController extends UserBaseController
{

    public function __construct(private UserChangeProfilePictureService $userChangeProfilePictureService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->userChangeProfilePictureService->changeProfilePicture($request, $request->getClientIp());

        return $this->successResponse('Profile Picture has been changed successfully.');

    }

}
