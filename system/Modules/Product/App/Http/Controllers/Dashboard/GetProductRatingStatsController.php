<?php

namespace Modules\Product\App\Http\Controllers\Dashboard;

use Modules\Product\App\Service\Admin\Dashboard\GetProductRatingStatsService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetProductRatingStatsController extends AdminBaseController
{

    public function __construct(private GetProductRatingStatsService $getProductRatingStatsService)
    {
    }

    public function __invoke()
    {
        $data = $this->getProductRatingStatsService->getReviewStats();
        return $this->successResponse('Data has been fetched successfully.', $data);
    }

}
