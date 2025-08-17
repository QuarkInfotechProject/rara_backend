<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Product\App\Service\User\PaginateProductListService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class FetchProductListController extends UserBaseController
{

    public function __construct(private PaginateProductListService $fetchProductListService)
    {
    }

    public function __invoke(Request $request)
    {
        $list = $this->fetchProductListService->getPaginatedProducts($request->get('filters'));
        return $this->successResponse('List has been fetched successfully', $list);
    }

}
