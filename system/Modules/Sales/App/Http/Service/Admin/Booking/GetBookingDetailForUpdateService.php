<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Modules\Sales\App\Models\AdditionalBookingProduct;
use Modules\Sales\App\Models\Booking;
use Modules\Shared\Exception\Exception;

class GetBookingDetailForUpdateService
{
    public function getBookingDetail($id): array
    {
        try {
            $booking = Booking::with(['agent:id,firstname,lastname', 'product:id,name,type'])
                ->findOrFail($id);

            $additionalProducts = AdditionalBookingProduct::where('booking_id', $booking->id)
                ->pluck('product_id')
                ->toArray();

            return [
                'id' => $booking->id,
                'product_id' => $booking->product_id,
                'product_name' => $booking->product->name ?? null,
                'product_type' => $booking->product->type ?? null,
                'ceo' => $booking->ceo ?? null,
                'group_name' => $booking->group_name ?? null,
                'room_required' => $booking->room_required ?? null,
                'agent_id' => $booking->agent_id,
                'agent_name' => $booking->agent ? $booking->agent->firstname . ' ' . ($booking->agent->lastname ?? '') : null,
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
                'additional_products' => $additionalProducts,
                'note' => $booking->note,
                'additional_note' => $booking->additional_note,
                'ref_no' => $booking->ref_no,
                'user' => $booking->user ? [
                    'id' => $booking->user->id,
                    'name' => $booking->user->full_name,
                    'email' => $booking->user->email,
                ] : null,
            ];
        } catch (Exception $exception) {
            throw $exception;

        }
    }

}
