<?php

namespace Modules\Blog\App\Http\Controllers\Blog;

use Modules\Blog\App\Service\UserListForSelectService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetUserListForSelectController extends AdminBaseController
{

    public function __construct(private UserListForSelectService $userListForSelectService)
    {
    }


    public function __invoke()
    {
        $userList = $this->userListForSelectService->getUserListForSelect();

        return $this->successResponse('Admin User List has been fetched successfully.', $userList);
    }

}
