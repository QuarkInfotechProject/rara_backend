<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListSafariForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListSafariForHomepageController extends UserBaseController
{

    public function __construct(private ListSafariForHomepageService $listSafariForHomepageService)
    {
    }


    public function __invoke()
    {
        $data = $this->listSafariForHomepageService->getSafariProducts();
        return $this->successResponse('Safari Product List for homepage has been fetched successfully.', $data);
    }

}
