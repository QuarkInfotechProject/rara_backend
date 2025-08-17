<?php

namespace Modules\Product\App\Http\Controllers\Product;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListManagerForSelectController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\ListManagerForSelectService $listManagerForSelectService)
    {
    }

    public function __invoke(Request $request)
    {
        $managerList = $this->listManagerForSelectService->listAllManagerForSelect();

        return $this->successResponse('Manager list has been fetched successfully.', $managerList);
    }
}
