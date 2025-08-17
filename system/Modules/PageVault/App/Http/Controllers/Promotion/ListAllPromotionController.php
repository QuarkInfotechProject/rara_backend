<?php

namespace Modules\PageVault\App\Http\Controllers\Promotion;

use Modules\PageVault\App\Service\Promotion\ListAllPromotionService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListAllPromotionController extends AdminBaseController
{
    public function __construct(private ListAllPromotionService $listAllPromotionService)
    {
    }

    public function __invoke()
    {
       $list = $this->listAllPromotionService->getPromotionList();

       return $this->successResponse('Promotion list has been fetched successfully.', $list);
    }
}
