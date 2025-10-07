<?php

namespace Modules\Product\App\Http\Controllers\Product\Category;

use Modules\Shared\App\Http\Controllers\AdminBaseController;

class FetchAProductCategoryDetailController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Category\FetchProductCategoryDetailService $fetchProductCategoryDetailService )
    {
    }


    public function __invoke($id)
    {
        $detail = $this->fetchProductCategoryDetailService->getCategoryDetails($id);
        return $this->successResponse('Detail for category has been fetched successfully.', $detail);
    }


}
