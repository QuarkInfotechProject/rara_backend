<?php

namespace Modules\Sales\App\Http\Service\Admin\Review;

use Modules\Product\App\Models\ProductRatingReview;

class ReplyToReviewService
{

    public function replyToReview($data)
    {
        $review = ProductRatingReview::findOrFail($data['id']);
        $review->reply_to_public_review = $data['reply'];

        $review->save();

    }



}
