<?php

namespace Modules\Product\App\Service\User;

use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductRatingReview;

class GetProductDetailCommentService
{
    public function getReviews($slug, $perPage = 10, $page = 1)
    {
        try {
            $product = Product::where('slug', $slug)->firstOrFail();

            $reviews = ProductRatingReview::with(['user:id,full_name,country'])
                ->where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            // If the requested page is greater than the last page, adjust to the last page
            if ($reviews->currentPage() > $reviews->lastPage()) {
                $reviews = ProductRatingReview::with(['user:id,full_name,country'])
                    ->where('product_id', $product->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage, ['*'], 'page', $reviews->lastPage());
            }

            $reviewsData = $reviews->getCollection()->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => [
                        'id' => $review->user->id,
                        'name' => $review->user->full_name,
                        'country' => $review->user->country,
                    ],
                    'cleanliness' => $review->cleanliness,
                    'hospitality' => $review->hospitality,
                    'value_for_money' => $review->value_for_money,
                    'communication' => $review->communication,
                    'overall_rating' => $review->overall_rating,
                    'public_review' => $review->public_review,
                    'reply_to_public_review' => $review->reply_to_public_review,
                    'reviewed_at' => $review->created_at->toDateTimeString(),
                ];
            });

            $reviewStats = $this->getReviewStatistics($product->id);

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'review_stats' => $reviewStats,
                'reviews' => [
                    'data' => $reviewsData,
                    'current_page' => $reviews->currentPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                    'last_page' => $reviews->lastPage(),
                ],
            ];
        } catch (\Exception $exception) {
            return [
                'code' => -1,
                'message' => 'An error occurred while fetching reviews.',
                'data' => null,
            ];
        }
    }

    private function getReviewStatistics($productId)
    {
        $reviews = ProductRatingReview::where('product_id', $productId)->get();
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('overall_rating');

        $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

        foreach ($reviews as $review) {
            $rating = $review->overall_rating;
            if ($rating >= 1 && $rating < 2) {
                $ratingCounts[1]++;
            } elseif ($rating >= 2 && $rating < 3) {
                $ratingCounts[2]++;
            } elseif ($rating >= 3 && $rating < 4) {
                $ratingCounts[3]++;
            } elseif ($rating >= 4 && $rating < 5) {
                $ratingCounts[4]++;
            } elseif ($rating == 5) {
                $ratingCounts[5]++;
            }
        }

        $ratingDistribution = [];
        foreach ($ratingCounts as $rating => $count) {
            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
            $ratingDistribution[$rating] = round($percentage, 2);
        }

        $totalComments = $reviews->filter(function ($review) {
            return !is_null($review->public_review);
        })->count();

        return [
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 2),
            'total_comments' => $totalComments,
            'rating_distribution' => $ratingDistribution,
        ];
    }
}
