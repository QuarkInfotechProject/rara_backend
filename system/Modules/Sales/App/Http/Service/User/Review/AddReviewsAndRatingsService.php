<?php

namespace Modules\Sales\App\Http\Service\User\Review;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductRatingReview;
//use Modules\Shared\App\Models\File; // Assuming you have a File model

class AddReviewsAndRatingsService
{
    public function submitReview(array $data)
    {
        try {
            $isAuthenticated = auth()->check();

            // Guest email check
            if (!$isAuthenticated && empty($data['email'])) {
                throw ValidationException::withMessages([
                    'email' => ['Email is required for guest reviews.']
                ]);
            }

            // Check for existing review
            $existingReviewQuery = ProductRatingReview::where('product_id', $data['product_id']);
            if ($isAuthenticated) {
                $existingReviewQuery->where('user_id', auth()->id());
            } else {
                $existingReviewQuery->where('email', $data['email'])
                    ->whereNull('user_id');
            }

            if ($existingReviewQuery->exists()) {
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
                'files' => 'required|array',
                'files.review_profile' => 'required|array|min:1',
                'files.review_profile.*' => 'exists:files,id',
            ];

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
                'approved' => 0,
            ];

            if ($isAuthenticated) {
                $reviewData['user_id'] = auth()->id();
                $reviewData['email'] = auth()->user()->email;
            } else {
                $reviewData['user_id'] = null;
                $reviewData['email'] = $data['email'];
            }

            $review = ProductRatingReview::create($reviewData);

            // Attach files after saving - make sure to specify the type
            if (!empty($data['files']['review_profile'])) {
                $review->syncFiles([
                    'review_profile' => $data['files']['review_profile']
                ]);
            }

            // Update product ratings
            $this->updateProductRatings($data['product_id']);

            // Get files using the same pattern as product service
            $reviewFiles = $this->getMediaFiles($review, 'review_profile', true);

            return [
                'code' => 0,
                'message' => 'Thank you for your review! It has been added successfully.',
                'data' => array_merge($review->toArray(), [
                    'files' => $reviewFiles
                ])
            ];

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Review service error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
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

    private function getMediaFiles($entity, $type, $multiple = false)
    {
        $baseImageFiles = $entity->filterFiles($type)->get();

        if ($multiple) {
            return $baseImageFiles->map(function ($file) {
                return [
                    'id' => $file->id,
                    'url' => $file->path . '/' . $file->temp_filename,
                ];
            })->toArray();
        } else {
            $file = $baseImageFiles->first();
            return $file ? [
                'id' => $file->id,
                'url' => $file->path . '/' . $file->temp_filename,
            ] : null;
        }
    }
}
