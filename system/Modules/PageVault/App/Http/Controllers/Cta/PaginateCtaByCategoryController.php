<?php

namespace Modules\PageVault\App\Http\Controllers\Cta;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\Cta\PaginateCtaByCategoryService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateCtaByCategoryController extends AdminBaseController
{
    public function __construct(private PaginateCtaByCategoryService $paginateCtaByCategoryService)
    {
    }

    public function __invoke(Request $request)
    {
        $paginate = $this->paginateCtaByCategoryService->paginateFaq($request->get('filters'));
        return $this->successResponse('Paginated list has been fetched successfully.', $paginate);
    }
}
