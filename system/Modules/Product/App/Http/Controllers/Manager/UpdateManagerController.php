<?php

namespace Modules\Product\App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateManagerController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Manager\UpdateManagerService $updateManagerService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updateManagerService->updateManager($request->request->all(), $request->getClientIp());

        return $this->successResponse('Manager has been updated successfully.');
    }

}
