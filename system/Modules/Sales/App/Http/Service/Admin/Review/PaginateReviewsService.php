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

        $query->select('id', 'product_id', 'user_id', 'email','full_name', 'overall_rating', 'public_review', 'approved');

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage)->through(function ($review) {
            return [
                'id' => $review->id,
                'product_id' => $review->product_id,
                'user_id' => $review->user_id,
                'email' => $review->email,
                'full_name' => $review->user
                    ? $review->user->full_name
                    : ($review->full_name ?? null),
                'overall_rating' => $review->overall_rating,
                'public_review' => $review->public_review,
                'approved' => $review->approved,
                'product' => $review->product ? [
                    'id' => $review->product->id,
                    'name' => $review->product->name,
                ] : null,
            ];
        });
    }


}
