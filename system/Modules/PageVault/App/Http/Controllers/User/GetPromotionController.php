<?php

namespace Modules\PageVault\App\Http\Controllers\User;

use Modules\PageVault\App\Service\User\GetPromotionService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetPromotionController extends UserBaseController
{

    public function __construct(private GetPromotionService $getPromotionService)
    {
    }

    public function __invoke()
    {
        $data = $this->getPromotionService->execute();
        return $this->successResponse('Promotion List has been fetched successfully', $data);
    }

}
