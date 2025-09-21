<?php

namespace Modules\Sales\App\Http\Controllers\User\Review;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\User\Review\AddReviewsAndRatingsService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class AddReviewsAndRatingController extends UserBaseController
{

    public function __construct(private AddReviewsAndRatingsService $addReviewsAndRatingsService)
    {
    }

    public function __invoke(Request $request)
    {
        try {
            $review = $this->addReviewsAndRatingsService->submitReview($request->all());

            $message = auth()->check()
                ? 'Reviews and rating has been added successfully.'
                : 'Thank you for your review! It has been added successfully.';

            return $this->successResponse($message, $review);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Review submission error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return $this->errorResponse(
                'An error occurred while submitting the review: ' . $e->getMessage(),
                [],
                500
            );
        }
    }
}
