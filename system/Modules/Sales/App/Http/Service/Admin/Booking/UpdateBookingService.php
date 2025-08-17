<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
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

            $booking->update([
                'agent_id' => $validatedData['agent_id'] ?? $booking->agent_id,
                'from_date' => $validatedData['from_date'] ?? $booking->from_date,
                'to_date' => $validatedData['to_date'] ?? $booking->to_date,
                'guest_no' => $validatedData['adult'] ?? $booking->guest_no,
                'children' => $validatedData['children'] ?? $booking->children,
                'infant' => $validatedData['infant'] ?? $booking->infant,
                'type' => $validatedData['type'] ?? $booking->type,
                'fullname' => $validatedData['fullname'] ?? $booking->fullname,
                'mobile_number' => $validatedData['mobile_number'] ?? $booking->mobile_number,
                'email' => $validatedData['email'] ?? $booking->email,
                'country' => $validatedData['country'] ?? $booking->country,
                'note' => $validatedData['note'] ?? $booking->note,
                'status' => $validatedData['status'] ?? $booking->note,
                'ceo' => $data['ceo'] ?? null,
                'group_name' => $data['group_name'],
                'room_required' => $data['room_required'] ?? null,
                'additional_note' => $data['additional_note'] ?? null,
            ]);

            if (isset($validatedData['additional_products'])) {
                $this->updateAdditionalBookingProducts($booking, $validatedData['additional_products']);
            } else {
                $booking->additionalBookingProducts()->delete();
            }

            $user= $booking->user;

            if ($user) {
                $this->sendEmailOnTripCompleted($user);
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
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

        foreach ($additionalProducts as $productData) {
            $product = Product::findOrFail($productData);

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
            'agent_id' => 'nullable|exists:agents,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'adult' => 'sometimes|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infant' => 'nullable|integer|min:0',
            'type' => 'sometimes|in:booking,inquiry',
            'fullname' => 'sometimes|string|max:255',
            'mobile_number' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:255',
            'country' => 'sometimes|string|max:255',
            'note' => 'nullable|string',
            'status' => 'required|string|in:pending,in-progress,confirmed,cancelled,completed,no-show',
            'additional_products' => 'nullable|array',
            'ceo' => 'nullable|string',
            'group_name' => 'required|string|max:255',
            'room_required' => 'nullable|string',
            'additional_note' => 'nullable|string',
        ];

        $messages = [
            'id.required' => 'The booking ID is required.',
            'id.exists' => 'The specified booking does not exist.',
            'product_id.exists' => 'The selected product does not exist.',
            'agent_id.exists' => 'The selected agent does not exist.',
            'from_date.date' => 'The start date must be a valid date.',
            'to_date.date' => 'The end date must be a valid date.',
            'to_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'adult.integer' => 'The number of adults must be an integer.',
            'adult.min' => 'There must be at least one adult.',
            'children.integer' => 'The number of children must be an integer.',
            'children.min' => 'The number of children cannot be negative.',
            'infant.integer' => 'The number of infants must be an integer.',
            'infant.min' => 'The number of infants cannot be negative.',
            'type.in' => 'The booking type must be either "booking" or "inquiry".',
            'fullname.string' => 'The full name must be a string.',
            'mobile_number.string' => 'The mobile number must be a string.',
            'email.email' => 'Please provide a valid email address.',
            'country.string' => 'The country must be a string.',
            'group_name.required' => 'Group Name is required.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function sendEmailOnTripCompleted(User $user)
    {
        $template = EmailTemplate::where('name', 'trip_completed')->first();

        $message = strtr($template->message, [
            '{FULLNAME}' => $user->full_name,
        ]);

        $userForgotPasswordDTO = UserTripCompletedDTO::from([
            'title' => $template->title,
            'subject' => $template->subject,
            'description' => $message,
            'email' => $user->email,
        ]);

        Event::dispatch(new SendTripCompletedMail($userForgotPasswordDTO));
    }
}
