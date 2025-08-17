<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListAllProductSlugService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListAllProductSlugController extends UserBaseController
{

    public function __construct(private ListAllProductSlugService $listAllProductSlugService)
    {
    }

    public function __invoke()
    {
        $data = $this->listAllProductSlugService->getSlugWithUpdatedAt();

        return $this->successResponse('Product slug list has been fetched successfully', $data);
    }

}
