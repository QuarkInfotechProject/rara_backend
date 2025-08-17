<?php

namespace Modules\PageVault\App\Http\Controllers\User;

use Modules\PageVault\App\Service\User\GetFaqsListByTypeService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetFaqsListByCategoryController extends UserBaseController
{

    public function __construct(private GetFaqsListByTypeService $getFaqsListByTypeService)
    {
    }

    public function __invoke($category)
    {
        $data = $this->getFaqsListByTypeService->listFaqs($category);

        return $this->successResponse('List has been fetched successfully.',  $data);
    }

}
