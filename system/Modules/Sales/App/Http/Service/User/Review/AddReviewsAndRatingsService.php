<?php

namespace Modules\Sales\App\Http\Service\User\Review;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductRatingReview;

class AddReviewsAndRatingsService
{

    public function submitReview($data)
    {
        try {
            // Check if user is authenticated
            $isAuthenticated = auth()->check();

            if (!$isAuthenticated) {
                // For guest users, email is required
                if (empty($data['email'])) {
                    throw ValidationException::withMessages([
                        'email' => ['Email is required for guest reviews.']
                    ]);
                }
            }

            // Check for existing review
            $existingReviewQuery = ProductRatingReview::where('product_id', $data['product_id']);

            if ($isAuthenticated) {
                $existingReviewQuery->where('user_id', auth()->id());
            } else {
                $existingReviewQuery->where('email', $data['email'])
                    ->whereNull('user_id');
            }

            $existingReview = $existingReviewQuery->first();

            if ($existingReview) {
                throw ValidationException::withMessages([
                    'review' => ['You have already submitted a review for this product.']
                ]);
            }

            // Validation rules
            $rules = [
                'product_id' => 'required|exists:products,id',
                'cleanliness' => 'required|numeric|min:1|max:5',
                'hospitality' => 'required|numeric|min:1|max:5',
                'value_for_money' => 'required|numeric|min:1|max:5',
                'communication' => 'required|numeric|min:1|max:5',
                'public_review' => 'required|string|min:10',
                'private_review' => 'nullable|string',
            ];

            // Add email validation for guest users
            if (!$isAuthenticated) {
                $rules['email'] = 'required|email';
            }

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Calculate overall rating
            $overallRating = ($data['value_for_money'] + $data['communication'] + $data['cleanliness'] + $data['hospitality']) / 4;

            // Prepare review data
            $reviewData = [
                'product_id' => $data['product_id'],
                'cleanliness' => $data['cleanliness'],
                'hospitality' => $data['hospitality'],
                'value_for_money' => $data['value_for_money'],
                'communication' => $data['communication'],
                'overall_rating' => $overallRating,
                'public_review' => $data['public_review'],
                'private_review' => $data['private_review'] ?? null,
                'approved' =>0,
            ];

            // Add user_id or email based on authentication status
            if ($isAuthenticated) {
                $reviewData['user_id'] = auth()->id();
                $reviewData['email'] = auth()->user()->email; // Store email for authenticated users too
            } else {
                $reviewData['user_id'] = null;
                $reviewData['email'] = $data['email'];
            }

            $review = new ProductRatingReview($reviewData);
            $review->save();

            // Update product ratings
            $this->updateProductRatings($data['product_id']);

            return $review;

        } catch (ValidationException $e) {
            throw $e; // Re-throw validation exceptions
        } catch (\Exception $e) {
            \Log::error('Review service error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e; // Re-throw to be caught by controller
        }
    }

    private function updateProductRatings($productId)
    {
        $product = Product::findOrFail($productId);

        $totalReviews = ProductRatingReview::where('product_id', $productId)->count();
        $totalRatingSum = ProductRatingReview::where('product_id', $productId)->sum('overall_rating');
        $averageRating = $totalReviews > 0 ? $totalRatingSum / $totalReviews : 0;

        $product->average_rating = $averageRating;
        $product->total_rating = $totalReviews;
        $product->total_comment = ProductRatingReview::where('product_id', $productId)
            ->whereNotNull('public_review')
            ->count();

        $product->save();
    }
}
