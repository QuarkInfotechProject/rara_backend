<?php

namespace Modules\Product\App\Service\User;

use Modules\Product\App\Models\ProductRatingReview;

class ListReviewsAndRatingForHomepageService
{
    public function getLatestHighRatedReviews($limit = 10)
    {
        return ProductRatingReview::select(
            'product_rating_reviews.id',
            'product_rating_reviews.public_review',
            'product_rating_reviews.overall_rating',
            'product_rating_reviews.created_at',
            'products.name as product_name',
            'users.full_name as user_name',
            'product_rating_reviews.full_name as guest_name',
            'users.country as user_country'
        )
            ->join('products', 'product_rating_reviews.product_id', '=', 'products.id')
            ->leftJoin('users', 'product_rating_reviews.user_id', '=', 'users.id')
            ->where('product_rating_reviews.overall_rating', '>=', 4)
            ->where('product_rating_reviews.approved', true)
            ->orderBy('product_rating_reviews.created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($review) {
                $review->user_name = $review->user_name ?? $review->guest_name ?? 'Guest';
                unset($review->guest_name);
                return $review;
            });
    }

}
