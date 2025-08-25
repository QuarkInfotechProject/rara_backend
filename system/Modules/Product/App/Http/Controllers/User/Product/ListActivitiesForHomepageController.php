<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListActivitiesForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListActivitiesForHomepageController extends UserBaseController
{

    public function __construct(private ListActivitiesForHomepageService $listActivitiesForHomepageService)
    {
    }


    public function __invoke()
    {
        $data = $this->listActivitiesForHomepageService->getActivitiesProducts();
        return $this->successResponse('Activities Product List for homepage has been fetched successfully.', $data);
    }

}
