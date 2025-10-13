<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Modules\Sales\App\Models\AdditionalBookingProduct;
use Modules\Sales\App\Models\Booking;
use Modules\Product\App\Models\Product;
use Modules\Shared\Exception\Exception;

class GetBookingDetailForUpdateService
{
    public function getBookingDetail($id): array
    {
        try {
            // Load booking with agent and product
            $booking = Booking::with(['agent:id,firstname,lastname', 'product:id,name,type'])
                ->findOrFail($id);

            // Additional products linked to booking
            $additionalProducts = AdditionalBookingProduct::where('booking_id', $booking->id)
                ->with('product:id,name')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->product_id,
                        'name' => $item->product->name ?? null,
                    ];
                })
                ->toArray();

            // Preference activities as products
            $preferenceActivityIds = $booking->preference_activities
                ? json_decode($booking->preference_activities, true)
                : [];

            $preferenceActivities = Product::whereIn('id', $preferenceActivityIds)
                ->get(['id', 'name'])
                ->toArray();

            return [
                'id' => $booking->id,
                'product_id' => $booking->product_id,
                'product_name' => $booking->product->name ?? null,
                'product_type' => $booking->product->type ?? null,
                'from_date' => $booking->from_date,
                'to_date' => $booking->to_date,
                'adult' => $booking->adult,
                'children' => $booking->children,
                'infant' => $booking->infant,
                'type' => $booking->type,
                'status' => $booking->status,
                'fullname' => $booking->fullname,
                'mobile_number' => $booking->mobile_number,
                'email' => $booking->email,
                'country' => $booking->country,
                'note' => $booking->note,
                'ref_no' => $booking->ref_no,
                'has_responded' => $booking->has_responded,
                'group_size' => $booking->group_size,
                'preferred_date' => $booking->preferred_date,
                'duration' => $booking->duration,
                'budget_range' => $booking->budget_range,
                'accommodation_preference' => $booking->accommodation_preference,
                'transportation_preference' => $booking->transportation_preference,
                'preference_activities' => $preferenceActivities,
                'special_message' => $booking->special_message,
                'special_requirement' => $booking->special_requirement,
                'desired_destination' => $booking->desired_destination,
            ];
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
