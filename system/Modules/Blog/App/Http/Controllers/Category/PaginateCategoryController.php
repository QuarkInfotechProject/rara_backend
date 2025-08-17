<?php

namespace Modules\Blog\App\Http\Controllers\Category;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Modules\Blog\App\Service\Category\PaginateCategoryService;

class PaginateCategoryController extends AdminBaseController
{

    public function __construct(private PaginateCategoryService $paginateCategoryService)
    {
    }

    public function __invoke(Request $request)
    {
        $paginatedCategories = $this->paginateCategoryService->getPaginatedCategories($request->get('filters'));

        return $this->successResponse('Paginated blog category list has been fetched successfully.', $paginatedCategories);
    }

}
