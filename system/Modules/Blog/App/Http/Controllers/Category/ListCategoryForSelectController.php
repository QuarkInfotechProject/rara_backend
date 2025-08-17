<?php

namespace Modules\Blog\App\Http\Controllers\Category;

use Modules\Blog\App\Service\Category\ListCategoryForSelectService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListCategoryForSelectController extends AdminBaseController
{

    public function __construct(private ListCategoryForSelectService $listCategoryForSelectService)
    {
    }

    public function __invoke()
    {
        $list = $this->listCategoryForSelectService->getAllCategoriesForSelect();

        return $this->successResponse('Blog category list has been fetched successfully.', $list);
    }
}
