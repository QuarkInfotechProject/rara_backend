<?php

namespace Modules\Sales\App\Http\Controllers\User\Review;

use Modules\Sales\App\Http\Service\User\Review\GetBookingHistoryWithReviewsAndRatingService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetBookingHistoryWithReviewsAndRatingController extends UserBaseController
{
    public function __construct(private GetBookingHistoryWithReviewsAndRatingService $getBookingHistoryWithReviewsAndRatingService)
    {
    }

    public function __invoke()
    {
        $data = $this->getBookingHistoryWithReviewsAndRatingService->getUserStaysAndReviews();

        return $this->successResponse('Booking History has been fetched successfully.', $data);
    }

}
