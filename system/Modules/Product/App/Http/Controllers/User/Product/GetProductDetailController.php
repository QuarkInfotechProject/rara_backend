<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Illuminate\Http\Request;
use Modules\Product\App\Service\User\GetProductDetailService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetProductDetailController extends UserBaseController
{

    public function __construct(private GetProductDetailService $getProductDetailService)
    {
    }


    public function __invoke($slug)
    {
        $detail = $this->getProductDetailService->getHomestayDetails($slug);

       return $this->successResponse('Product detail has been fetched successfully', $detail);
    }

}
