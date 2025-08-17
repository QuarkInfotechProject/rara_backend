<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListProductForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListProductForHomepageController extends UserBaseController
{

    public function __construct(private ListProductForHomepageService $listProductForHomepageService)
    {
    }


    public function __invoke($type)
    {
        $data = $this->listProductForHomepageService->getPaginatedProducts($type);
        return $this->successResponse('Product List for homepage has been fetched successfully.', $data);
    }

}
