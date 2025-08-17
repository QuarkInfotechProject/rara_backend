<?php

namespace Modules\User\Service\Profile;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Product\App\Models\ProductRatingReview;
use Modules\Sales\App\Models\Booking;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;

class UserProfileShowService
{
    const THUMBNAIL_PATH = 'Thumbnail/';

    public function show()
    {
        if (!Auth::check()) {
            throw new Exception('User not authenticated.', ErrorCode::UNAUTHORIZED);
        }

        $user = Auth::user()->only([
            'full_name',
            'phone_no',
            'email',
            'offers_notification',
            'country',
            'profile_picture',
            'oauth_type'
        ]);

        $profilePictureUrls = $this->getProfilePictureUrls($user['profile_picture']);

        return [
            'fullName' => $user['full_name'],
            'phone' => $user['phone_no'] ?? '',
            'email' => $user['email'],
            'country' => $user['country'] ?? '',
            'offersNotification' => $user['offers_notification'],
            'profilePictureUrl' => $profilePictureUrls['full'],
            'profilePictureThumbnailUrl' => $profilePictureUrls['thumbnail']
        ];
    }

    private function getProfilePictureUrls($profilePicture)
    {
        if (empty($profilePicture)) {
            return [
                'full' => null,
                'thumbnail' => null,
            ];
        }

        // Check if the profile picture is a URL (likely from Google OAuth)
        if (filter_var($profilePicture, FILTER_VALIDATE_URL)) {
            return [
                'full' => $profilePicture,
                'thumbnail' => $profilePicture,
            ];
        }

        $basePath = 'modules/files/';
        return [
            'full' => url($basePath . $profilePicture),
            'thumbnail' => url($basePath . self::THUMBNAIL_PATH . $profilePicture),
        ];
    }

    public function getUserStaysAndReviews()
    {
        $userId = auth()->id();

        $completedBookings = Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->orderBy('to_date', 'desc')
            ->get();

        $reviewsNeeded = 0;

        $completedBookings->map(function ($booking) use (&$reviewsNeeded) {
            $review = ProductRatingReview::where('product_id', $booking->product_id)
                ->where('user_id', $booking->user_id)
                ->where('approved', true)
                ->first();

            $isEligibleForReview = $this->isEligibleForReview($booking, $review);

            if ($isEligibleForReview) {
                $reviewsNeeded++;
            }

            return $reviewsNeeded;
        });

        return $reviewsNeeded;
    }

    private function isEligibleForReview($booking, $review)
    {
        $isRecent = Carbon::parse($booking->to_date)->diffInDays(Carbon::now()) <= 300;

        return !$review && $isRecent;
    }
}
