<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListExploreForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListExploreProductForHomepageController extends UserBaseController
{

    public function __construct(private ListExploreForHomepageService $listExploreForHomepageService)
    {
    }


    public function __invoke()
    {
        $data = $this->listExploreForHomepageService->getExploreProducts();
        return $this->successResponse('Explore service Product List for homepage has been fetched successfully.', $data);
    }

}
