<?php

namespace Modules\PageVault\App\Http\Controllers\Promotion;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\Promotion\UpdatePromotionService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdatePromotionController extends AdminBaseController
{
    public function __construct(private UpdatePromotionService $updatePromotionService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updatePromotionService->updatePromotion($request->request->all());
        return $this->successResponse('Promotion has been updated successfully.');
    }
}
