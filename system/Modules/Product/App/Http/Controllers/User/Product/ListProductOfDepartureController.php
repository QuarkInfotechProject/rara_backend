<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListProductOfDepartureService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListProductOfDepartureController extends UserBaseController
{
    public function __construct(private ListProductOfDepartureService $listProductOfDepartureService)
    {
    }
    public function __invoke()
    {
        $search = request()->query('search');
        $data = $this->listProductOfDepartureService->getProductsByDeparture($search);

        return $this->successResponse('Product List fetched successfully.', $data);
    }
}
