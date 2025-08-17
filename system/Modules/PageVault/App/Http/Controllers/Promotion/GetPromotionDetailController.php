<?php

namespace Modules\PageVault\App\Http\Controllers\Promotion;

use Modules\PageVault\App\Service\Promotion\GetPromotionDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetPromotionDetailController extends AdminBaseController
{
    public function __construct(private GetPromotionDetailService $getPromotionDetailService)
    {
    }

    public function __invoke($id)
    {
        $detail = $this->getPromotionDetailService->getPromotionDetail($id);
        return $this->successResponse('Promotion detail has been fetched successfully.', $detail);
    }
}
