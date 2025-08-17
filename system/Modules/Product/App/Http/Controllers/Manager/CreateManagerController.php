<?php

namespace Modules\Product\App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Manager\CreateManagerService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateManagerController extends AdminBaseController
{

    public function __construct(private CreateManagerService $createManagerService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createManagerService->addManager($request->request->all(), $request->getClientIp());

        return $this->successResponse('Manager has been created successfully.');
    }

}
