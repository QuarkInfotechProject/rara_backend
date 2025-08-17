<?php

namespace Modules\Sales\App\Http\Service\Admin\Review;

use Modules\Product\App\Models\ProductRatingReview;

class ApproveToggleReviewService
{

    public function toggleApproval($reviewId)
    {
        $review = ProductRatingReview::findOrFail($reviewId);

        $review->approved = !$review->approved;

        $review->save();

        return $review;
    }

}
