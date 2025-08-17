<?php

namespace Modules\Product\App\Http\Controllers\Manager;

use Modules\Product\App\Service\Admin\Manager\GetManagerDetailForUpdateService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetManagerDetailForUpdateController extends AdminBaseController
{

    public function __construct(private GetManagerDetailForUpdateService $getManagerDetailForUpdateService)
    {
    }

    public function __invoke($id)
    {
        $managerDetail = $this->getManagerDetailForUpdateService->getManagerDetailForUpdate($id);

        return $this->successResponse('Manager detail has been fetched successfully.', $managerDetail);
    }

}
