<?php

namespace Modules\Blog\App\Http\Controllers\User;

use Modules\Blog\App\Service\User\ListCategoryForFilterService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListCategoriesForSelectController extends UserBaseController
{


    public function __construct(private ListCategoryForFilterService $listCategoryForFilterService)
    {
    }


    public function __invoke()
    {
        $data = $this->listCategoryForFilterService->getCategoriesForSelect();
        return $this->successResponse('Category List has been fetched successfully.', $data);
    }

}
