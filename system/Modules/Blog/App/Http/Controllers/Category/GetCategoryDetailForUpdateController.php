<?php

namespace Modules\Blog\App\Http\Controllers\Category;

use Modules\Blog\App\Service\Category\GetCategoryDetailForUpdateService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetCategoryDetailForUpdateController extends AdminBaseController
{

    public function __construct(private GetCategoryDetailForUpdateService $getCategoryDetailForUpdateService)
    {
    }


    public function __invoke(int $id)
    {
        $categoryDetail = $this->getCategoryDetailForUpdateService->getCategoryDetailForUpdate($id);

        return $this->successResponse('Blog category detail has been fetched successfully.', $categoryDetail);
    }
}
