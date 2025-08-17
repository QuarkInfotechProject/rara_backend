<?php

namespace Modules\User\App\Http\Controllers\Admin;

use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Modules\User\Service\Admin\ViewUserService;

class ViewUserController extends AdminBaseController
{

    public function __construct(private ViewUserService $viewUserService)
    {
    }

    public function __invoke($id)
    {
        $data = $this->viewUserService->getUserDetailById($id);

        return $this->successResponse('User detail has been fetched successfully.', $data);
    }

}
