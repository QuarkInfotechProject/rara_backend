<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListProductOfActivitiesService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListProductOfActivitiesController extends UserBaseController
{
    public function __construct(private ListProductOfActivitiesService $listProductOfActivitiesService)
    {
    }
    public function __invoke()
    {
        $data = $this->listProductOfActivitiesService->getProductsByActivities();
        return $this->successResponse('Product List fetched successfully.', $data);
    }
}
