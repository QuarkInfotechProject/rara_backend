<?php

namespace Modules\Product\App\Http\Controllers\Product\Category;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Category\PaginateProductCategoryService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateProductCategoryController extends AdminBaseController
{

    public function __construct(private PaginateProductCategoryService $paginateProductCategoryService)
    {
    }

    public function __invoke(Request $request)
    {
        $filters = $request->get('filters', []);

        $data = $this->paginateProductCategoryService->paginate($filters);

        return $this->successResponse('Paginated category has been fetched successfully.', $data, 200);

    }

}
