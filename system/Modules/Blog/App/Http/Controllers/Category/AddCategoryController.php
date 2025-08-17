<?php

namespace Modules\Blog\App\Http\Controllers\Category;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\Category\AddCategoryService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class AddCategoryController extends AdminBaseController
{

    public function __construct(private AddCategoryService $addCategoryService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->addCategoryService->addCategory($request->request->all(), $request->getClientIp());

        return $this->successResponse('Blog category has been added successfully.');
    }
}
