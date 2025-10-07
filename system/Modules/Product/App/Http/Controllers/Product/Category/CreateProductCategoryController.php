<?php

namespace Modules\Product\App\Http\Controllers\Product\Category;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateProductCategoryController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Category\CreateProductCategoryService $createProductCategoryService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createProductCategoryService->createProductCategory($request->all(), $request->getClientIp(), $request);
        return $this->successResponse('Category has been created successfully.');
    }


}
