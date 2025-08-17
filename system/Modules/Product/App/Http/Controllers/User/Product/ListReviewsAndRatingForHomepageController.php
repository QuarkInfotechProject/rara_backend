<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListReviewsAndRatingForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListReviewsAndRatingForHomepageController extends UserBaseController
{

    public function __construct(private ListReviewsAndRatingForHomepageService $listReviewsAndRatingForHomepageService)
    {
    }

    public function __invoke()
    {
        $data = $this->listReviewsAndRatingForHomepageService->getLatestHighRatedReviews();

        return $this->successResponse('Review and rating has been fetched successfully.', $data);
    }

}
