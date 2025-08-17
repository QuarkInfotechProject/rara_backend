<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Illuminate\Support\Facades\DB;
use Modules\Sales\App\Models\Booking;
use Modules\Shared\Exception\Exception;

class GetBookingDetailService
{
    public function getBookingDetail($id): array
    {
        try {

            $booking = Booking::with(['additionalBookingProducts', 'product', 'agent', 'user'])->findOrFail($id);

            return [
                'booking_info' => [
                    'id' => $booking->id,
                    'type' => $booking->type,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                ],
                'product' => [
                    'name' => $booking->product->name,
                    'short_code' => $booking->product->short_code,
                    'type' => $booking->product->type,
                ],
                'booking_details' => [
                    'from_date' => $booking->from_date,
                    'to_date' => $booking->to_date,
                    'adult' => $booking->adult,
                    'children' => $booking->children,
                    'infant' => $booking->infant,
                    'ref_no' => $booking->ref_no,
                    'ceo' => $booking->ceo ?? null,
                    'group_name' => $booking->group_name ?? null,
                    'room_required' => $booking->room_required ?? null,
                ],
                'customer_info' => [
                    'fullname' => $booking->fullname,
                    'mobile_number' => $booking->mobile_number,
                    'email' => $booking->email,
                    'country' => $booking->country,
                ],
                'agent' => $booking->agent ? [
                    'name' => $booking->agent->firstname . ' ' . $booking->agent->lastname,
                    'email' => $booking->agent->email,
                    'phone' => $booking->agent->phone,
                    'company' => $booking->agent->company,
                    'website' => $booking->agent->website,
                    'full_address' => implode(', ', array_filter([
                        $booking->agent->address,
                        $booking->agent->city,
                        $booking->agent->country,
                        $booking->agent->postal_code
                    ])),
                ] : null,
                'additional_products' => $booking->additionalBookingProducts->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'description' => $product->description,
                        'id' => $product->product_id,
                    ];
                }),
                'additional_info' => [
                    'note' => $booking->note,
                    'additional_note' => $booking->additional_note,
                ],
                'user' => $booking->user ? [
                    'name' => $booking->user->full_name,
                    'email' => $booking->user->email,
                ] : null,
            ];

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
