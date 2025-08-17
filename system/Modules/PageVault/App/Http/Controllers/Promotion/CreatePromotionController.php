<?php

namespace Modules\PageVault\App\Http\Controllers\Promotion;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\Promotion\CreatePromotionService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreatePromotionController extends AdminBaseController
{
    public function __construct(private CreatePromotionService $createPromotionService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createPromotionService->createWhyUs($request->request->all());

        return $this->successResponse('Promotion has been created successfully.');
    }

}
