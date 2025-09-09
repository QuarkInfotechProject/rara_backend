<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListTrekForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListTrekForHomepageController extends UserBaseController
{

    public function __construct(private ListTrekForHomepageService $listTrekForHomepageService)
    {
    }


    public function __invoke()
    {
        $data = $this->listTrekForHomepageService->getTrekProducts();
        return $this->successResponse('Trek Product List for homepage has been fetched successfully.', $data);
    }

}
