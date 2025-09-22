<?php

namespace Modules\Sales\App\Http\Service\Admin\Review;

use Modules\Product\App\Models\ProductRatingReview;

class PaginateReviewsService
{
    public function getPaginatedReviews($filters = [], $perPage = 10)
    {
        $query = ProductRatingReview::query();

        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['approved'])) {
            $query->where('approved', $filters['approved']);
        }

        $query->with(['product:id,name', 'user:id,full_name,email']);

        $query->select('id', 'product_id', 'user_id', 'email', 'overall_rating', 'public_review', 'approved');

        return $query->paginate($perPage);
    }


}
