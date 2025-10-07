<?php

namespace Modules\Product\App\Http\Controllers\Product\Category;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Category\UpdateProductCategoryService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateProductCategoryController extends AdminBaseController
{

    public function __construct(private UpdateProductCategoryService $updateProductCategoryService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->updateProductCategoryService->updateCategory($request->request->all(), $request->getClientIp(), $request);

        return $this->successResponse('Category has been updated successfully.');
    }

}
