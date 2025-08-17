<?php

namespace Modules\Blog\App\Http\Controllers\Category;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\Category\UpdateCategoryService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateCategoryController extends AdminBaseController
{

    public function __construct(private UpdateCategoryService $updateCategoryService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updateCategoryService->updateCategory($request->request->all(), $request->getClientIp());

        return $this->successResponse('Blog category has been updated successfully');
    }
}
