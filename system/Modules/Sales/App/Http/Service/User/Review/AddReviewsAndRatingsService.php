<?php

namespace Modules\Sales\App\Http\Service\User\Review;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductRatingReview;
use Modules\Sales\App\Models\Booking;

class AddReviewsAndRatingsService
{

    public function submitReview($data)
    {
        $booking = Booking::findOrFail($data['booking_id']);

        if ($booking->user_id !== auth()->id()) {
            throw ValidationException::withMessages([
                'booking' => ['You are not authorized to review this booking.']
            ]);
        }

        if ($booking->status !== 'completed') {
            throw ValidationException::withMessages([
                'booking' => ['You can only review completed bookings.']
            ]);
        }

//        $existingReview = ProductRatingReview::where('product_id', $booking->product_id)
//            ->where('user_id', auth()->id())
//            ->first();
//
//        if ($existingReview) {
//            throw ValidationException::withMessages([
//                'review' => ['You have already submitted a review for this booking.']
//            ]);
//        }

        $rules = [
            'cleanliness' => 'required|numeric|min:1|max:5',
            'hospitality' => 'required|numeric|min:1|max:5',
            'value_for_money' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
            'public_review' => 'required|string|min:10',
            'private_review' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $overallRating = ($data['value_for_money'] + $data['communication'] + $data['cleanliness'] + $data['hospitality']) / 4;
        $review = new ProductRatingReview([
            'product_id' => $booking->product_id,
            'user_id' => auth()->id(),
            'cleanliness' => $data['cleanliness'],
            'hospitality' => $data['hospitality'],
            'value_for_money' => $data['value_for_money'],
            'communication' => $data['communication'],
            'overall_rating' => $overallRating,
            'public_review' => $data['public_review'],
            'private_review' => $data['private_review'] ?? null,
            'approved' => 1
        ]);

        $review->save();

        $product = Product::findOrFail($booking->product_id);

        $totalReviews = ProductRatingReview::where('product_id', $booking->product_id)->count();
        $totalRatingSum = ProductRatingReview::where('product_id', $booking->product_id)->sum('overall_rating');
        $averageRating = $totalReviews > 0 ? $totalRatingSum / $totalReviews : 0;

        $product->average_rating = $averageRating;
        $product->total_rating = $totalReviews;
        $product->total_comment = ProductRatingReview::where('product_id', $booking->product_id)->whereNotNull('public_review')->count();

        $product->save();
    }

}
