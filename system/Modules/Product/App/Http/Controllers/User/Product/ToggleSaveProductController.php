<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\WishlistProductService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ToggleSaveProductController extends UserBaseController
{

    public function __construct(private WishlistProductService $saveProductService)
    {
    }

    public function __invoke($productId)
    {
        $message = $this->saveProductService->toggleSavedProduct($productId);

        return $this->successResponse($message);
    }
}
