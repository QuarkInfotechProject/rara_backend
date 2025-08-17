<?php

namespace Modules\User\App\Http\Controllers\Admin;

use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Modules\User\Service\Admin\ToggleBlockUnblockService;

class ToggleBlockUnblockUserController extends AdminBaseController
{

    public function __construct(private ToggleBlockUnblockService $toggleBlockUnblockService)
    {
    }

    public function __invoke($id)
    {
        $this->toggleBlockUnblockService->toggleUserStatus($id);

        return $this->successResponse('User has been blocked/unblocked successfully.');
    }

}
