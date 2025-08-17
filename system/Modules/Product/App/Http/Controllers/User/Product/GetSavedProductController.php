<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\GetSavedProductService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetSavedProductController extends UserBaseController
{

    public function __construct(private GetSavedProductService $getSavedProductService)
    {
    }

    public function __invoke()
    {
       $savedProduct = $this->getSavedProductService->getSavedProducts();

       return $this->successResponse('Saved Product has been fetched successfully', $savedProduct);
    }

}
