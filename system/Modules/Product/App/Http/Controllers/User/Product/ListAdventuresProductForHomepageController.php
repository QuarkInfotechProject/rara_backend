<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListAdventuresForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListAdventuresProductForHomepageController extends UserBaseController
{

    public function __construct(private ListAdventuresForHomepageService $listAdventuresForHomepageService)
    {
    }


    public function __invoke()
    {
        $data = $this->listAdventuresForHomepageService->getAdventuresProducts();
        return $this->successResponse('Adventures Product List for homepage has been fetched successfully.', $data);
    }

}
