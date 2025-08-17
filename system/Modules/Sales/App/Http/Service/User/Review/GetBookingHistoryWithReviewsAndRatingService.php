<?php

namespace Modules\Sales\App\Http\Service\User\Review;

use Carbon\Carbon;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductRatingReview;
use Modules\Sales\App\Models\Booking;

class GetBookingHistoryWithReviewsAndRatingService
{
    public function getUserStaysAndReviews()
    {
        $userId = auth()->id();
        $completedBookings = Booking::where('user_id', $userId)
//            ->where('status', 'completed')
            ->orderBy('to_date', 'desc')
            ->get();

        $reviewsNeeded = 0;
        $staysAndReviews = $completedBookings->map(function ($booking) use (&$reviewsNeeded) {
            $review = ProductRatingReview::where('product_id', $booking->product_id)
                ->where('user_id', $booking->user_id)
                ->where('approved', true)
                ->first();

            $product = Product::select('id', 'name', 'tagline', 'location', 'average_rating')
                ->find($booking->product_id);

            $isEligibleForReview = $this->isEligibleForReview($booking, $review);
            if ($isEligibleForReview) {
                $reviewsNeeded++;
            }

            return [
                'booking_id' => $booking->id,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'tagline' => $product->tagline,
                    'location' => $product->location,
                    'average_rating' => $product->average_rating,
                    'featured_image' => $this->getFeaturedImage($product),
                ],
                'from_date' => $booking->from_date,
                'to_date' => $booking->to_date,
                'status' => $booking->status,
                'review' => $review ? [
                    'id' => $review->id,
                    'overall_rating' => $review->overall_rating,
                    'cleanliness' => $review->cleanliness,
                    'hospitality' => $review->hospitality,
                    'value_for_money' => $review->value_for_money,
                    'communication' => $review->communication,
                    'public_review' => $review->public_review,
                    'private_review' => $review->private_review,
                    'reply_to_public_review' => $review->reply_to_public_review,
                    'review_date' => $review->created_at,
                ] : null,
                'review_eligible' => $isEligibleForReview,
            ];
        });

        return [
            'stays_and_reviews' => $staysAndReviews,
            'reviews_needed' => $reviewsNeeded,
        ];
    }

    private function isEligibleForReview($booking, $review)
    {
        $isRecent = Carbon::parse($booking->to_date)->diffInDays(Carbon::now()) <= 300;
        return !$review && $isRecent;
    }

    private function getFeaturedImage($product)
    {
        $featuredImage = $product->filterFiles('featuredImage')->first();
        return $featuredImage ? $featuredImage->path . '/' . $featuredImage->temp_filename : '';
    }
}
