<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Sales\App\Models\AdditionalBookingProduct;
use Modules\Sales\App\Models\Booking;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Modules\SystemConfiguration\App\Models\EmailTemplate;
use Modules\User\App\Events\SendTripCompletedMail;
use Modules\User\App\Models\User;
use Modules\User\DTO\UserTripCompletedDTO;

class UpdateBookingService
{
    public function updateBooking(array $data, $ipAddress)
    {
        $validatedData = $this->validateBookingUpdateData($data);

        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($validatedData['id']);
            $product = Product::findOrFail($validatedData['product_id'] ?? $booking->product_id);
            $isUser = auth()->check();

            $booking->update([
                'product_id' => $validatedData['product_id'] ?? $booking->product_id,
                'agent_id' => $validatedData['agent_id'] ?? $booking->agent_id,
                'user_id' => $isUser ? auth()->id() : $booking->user_id,
                'product_name' => $product->name,
                'product_type' => $product->type,
                'from_date' => $validatedData['from_date'] ?? $booking->from_date,
                'to_date' => $validatedData['to_date'] ?? $booking->to_date,
                'adult' => $validatedData['adult'] ?? $booking->adult,
                'children' => $validatedData['children'] ?? $booking->children,
                'infant' => $validatedData['infant'] ?? $booking->infant,
                'type' => $isUser ? 'inquiry' : 'custom',
                'status' => $validatedData['status'] ?? $booking->status,
                'fullname' => $validatedData['fullname'] ?? $booking->fullname,
                'mobile_number' => $validatedData['mobile_number'] ?? $booking->mobile_number,
                'email' => $validatedData['email'] ?? $booking->email,
                'country' => $validatedData['country'] ?? $booking->country,
                'note' => $validatedData['note'] ?? $booking->note,
                'has_responded' => $validatedData['has_responded'] ?? $booking->has_responded,
                'group_size' => $validatedData['group_size'] ?? $booking->group_size,
                'preferred_date' => $validatedData['preferred_date'] ?? $booking->preferred_date,
                'duration' => $validatedData['duration'] ?? $booking->duration,
                'budget_range' => $validatedData['budget_range'] ?? $booking->budget_range,
                'accommodation_preference' => $validatedData['accommodation_preference'] ?? $booking->accommodation_preference,
                'transportation_preference' => $validatedData['transportation_preference'] ?? $booking->transportation_preference,
                'preference_activities' => isset($validatedData['preference_activities'])
                    ? json_encode($validatedData['preference_activities'])
                    : $booking->preference_activities,
                'special_message' => $validatedData['special_message'] ?? $booking->special_message,
                'special_requirement' => $validatedData['special_requirement'] ?? $booking->special_requirement,
                'desired_destination' => $validatedData['desired_destination'] ?? $booking->desired_destination,
            ]);

            // Optional: regenerate ref_no if product changed
            if ($booking->wasChanged('product_id')) {
                $ref_no = sprintf(
                    "%s-%s-%04d",
                    now()->format('Ymd'),
                    strtoupper(substr($product->type, 0, 8)),
                    $booking->id
                );
                $booking->update(['ref_no' => $ref_no]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Return proper JSON error response instead of invalid HTTP code
            return response()->json([
                'status' => 0,
                'error' => 'Database error: ' . $e->getMessage(),
            ], 400);
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "{$booking->product_name} booking has been updated by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::AGENT_UPDATED,
            $ipAddress
        ));

        return $booking;
    }

    private function updateAdditionalBookingProducts(Booking $booking, array $additionalProducts): void
    {
        $booking->additionalBookingProducts()->whereNotIn('product_id', $additionalProducts)->delete();

        foreach ($additionalProducts as $productId) {
            $product = Product::findOrFail($productId);

            $booking->additionalBookingProducts()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'name' => $product->name,
                    'description' => $product->description,
                ]
            );
        }
    }

    private function validateBookingUpdateData(array $data)
    {
        $rules = [
            'id' => 'required|exists:bookings,id',
            'product_id' => 'sometimes|exists:products,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'adult' => 'sometimes|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'infant' => 'nullable|integer|min:0',
            'type' => 'sometimes|in:custom,inquiry',
            'fullname' => 'sometimes|string|max:255',
            'mobile_number' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:255',
            'country' => 'sometimes|string|max:255',
            'status' => 'required|string|in:pending,in-progress,confirmed,cancelled,completed,no-show',
            'room_required' => 'nullable|string',
            'has_responded' => 'nullable|boolean',
            'group_size' => 'nullable|string|max:255',
            'preferred_date' => 'nullable|date',
            'duration' => 'nullable|integer|min:1',
            'budget_range' => 'nullable|string|max:255',
            'accommodation_preference' => 'nullable|string',
            'transportation_preference' => 'nullable|string',
            'preference_activities' => 'nullable|array',
            'special_message' => 'nullable|string',
            'special_requirement' => 'nullable|string',
            'desired_destination' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function sendEmailOnTripCompleted(User $user)
    {
        $template = EmailTemplate::where('name', 'trip_completed')->first();
        if (!$template) return;

        $message = strtr($template->message, [
            '{FULLNAME}' => $user->full_name,
        ]);

        $userDTO = UserTripCompletedDTO::from([
            'title' => $template->title,
            'subject' => $template->subject,
            'description' => $message,
            'email' => $user->email,
        ]);

        Event::dispatch(new SendTripCompletedMail($userDTO));
    }
}
