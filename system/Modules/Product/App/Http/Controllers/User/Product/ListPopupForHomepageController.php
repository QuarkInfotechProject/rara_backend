<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListPopupForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListPopupForHomepageController extends UserBaseController
{

    public function __construct(private ListPopupForHomepageService $listPopupForHomepageService)
    {
    }


    public function __invoke()
    {
        $data = $this->listPopupForHomepageService->getPopups();
        return $this->successResponse('Trek Product List for homepage has been fetched successfully.', $data);
    }

}
