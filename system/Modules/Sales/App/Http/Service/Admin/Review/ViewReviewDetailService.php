<?php

namespace Modules\Sales\App\Http\Service\Admin\Review;

use Modules\Product\App\Models\ProductRatingReview;

class ViewReviewDetailService
{
    public function getReviewDetails($reviewId)
    {
        $review = ProductRatingReview::with(['product:id,name', 'user:id,full_name'])
            ->findOrFail($reviewId);

        return $review;
    }

}
