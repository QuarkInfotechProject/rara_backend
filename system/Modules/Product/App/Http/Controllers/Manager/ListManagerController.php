<?php

namespace Modules\Product\App\Http\Controllers\Manager;

use Modules\Product\App\Service\Admin\Manager\ListManagerService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListManagerController extends AdminBaseController
{

    public function __construct(private ListManagerService $listManagerService)
    {
    }

    public function __invoke()
    {
        $list = $this->listManagerService->getManagersList();

        return $this->successResponse('Manager List has been fetched successfully.', $list);
    }

}
