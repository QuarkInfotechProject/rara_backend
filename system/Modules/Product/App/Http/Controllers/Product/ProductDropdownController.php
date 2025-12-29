<?php

namespace Modules\Product\App\Http\Controllers\Product;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\ProductDropdownService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ProductDropdownController extends AdminBaseController
{
    public function __construct(private ProductDropdownService $productDropdownService)
    {
    }

    public function __invoke(Request $request)
    {
        $products = $this->productDropdownService->handle($request);

       return $this->successResponse('Product dropdown fetched successfully', $products);
    }

}
