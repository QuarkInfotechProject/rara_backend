<?php

namespace Modules\Product\App\Http\Controllers\Product\Category;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Category\ListActiveProductCategoryService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListActiveProductCategoryController extends AdminBaseController
{

    public function __construct(private ListActiveProductCategoryService $listActiveProductCategoryService)
    {
    }

    public function __invoke()
    {
        $data = $this->listActiveProductCategoryService->activeList();

        return $this->successResponse('Active categories have been fetched successfully.', $data);
    }
}
