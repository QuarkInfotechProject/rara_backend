<?php

namespace Modules\Product\App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Manager\DeleteManagerService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DeleteManagerController extends AdminBaseController
{

    public function __construct(private DeleteManagerService $deleteManagerService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->deleteManagerService->deleteManager($request->get('id'), $request->getClientIp());

        return $this->successResponse('Manager has been deleted successfully.');
    }

}
