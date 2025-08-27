<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListPopularProductForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListPopularProductForHomepageController extends UserBaseController
{

    public function __construct(private ListPopularProductForHomepageService $listPopularProductForHomepageService)
    {
    }


    public function __invoke($type)
    {
        $data = $this->listPopularProductForHomepageService->getPaginatedProducts($type);
        return $this->successResponse('Product List for homepage has been fetched successfully.', $data);
    }

}
