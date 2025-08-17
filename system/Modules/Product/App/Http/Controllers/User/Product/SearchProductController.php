<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\SearchProductService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class SearchProductController extends UserBaseController
{

    public function __construct(private SearchProductService $searchProductService)
    {
    }

    public function __invoke($search)
    {
        $data = $this->searchProductService->search($search);

        return $this->successResponse('search result has been fetched successfully.', $data);
    }
}
