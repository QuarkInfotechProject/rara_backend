<?php

namespace Modules\Blog\App\Http\Controllers\Category;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\Category\DeleteCategoryService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DeleteCategoryController extends AdminBaseController
{

    public function __construct(private DeleteCategoryService $deleteCategoryService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->deleteCategoryService->deleteCategory($request->get('id'), $request->getClientIp());

        return $this->successResponse('Blog category has been deleted successfully.');
    }

}
