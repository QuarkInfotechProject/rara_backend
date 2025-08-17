<?php

namespace Modules\Sales\App\Http\Controllers\User\Review;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\User\Review\AddReviewsAndRatingsService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class AddReviewsAndRatingController extends UserBaseController
{

    public function __construct(private AddReviewsAndRatingsService $addReviewsAndRatingsService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->addReviewsAndRatingsService->submitReview($request->request->all());
        return $this->successResponse('Reviews and rating has been added successfully.');
    }

}
