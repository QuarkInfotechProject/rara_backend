<?php

namespace Modules\User\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Modules\User\Service\Admin\PaginateUserService;

class PaginateUserController extends AdminBaseController
{

    public function __construct(private PaginateUserService $paginateUserService)
    {
    }

    public function __invoke(Request $request)
    {
        $data = $this->paginateUserService->getPaginatedUsers($request->get('filters'));

        return $this->successResponse('Paginated list has been fetched successfully.', $data);
    }
}
