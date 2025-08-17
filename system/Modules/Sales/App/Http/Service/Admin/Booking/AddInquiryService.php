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

class AddInquiryService
{
    public function createInquiry(array $data, $ipAddress)
    {
        $this->validateInquiryData($data);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($data['product_id']);

            $inquiry = Booking::create([
                'product_id' => $data['product_id'],
                'product_name' => $product->name,
                'product_type' => $product->type,
                'agent_id' => $data['agent_id'] ?? null,
                'user_id' => null,
                'from_date' => $data['from_date'] ?? null,
                'to_date' => $data['to_date'] ?? null,
                'adult' => $data['adult'],
                'children' => $data['children'] ?? 0,
                'infant' => $data['infant'] ?? 0,
                'type' => $data['type'] ?? 'inquiry',
                'status' => 'pending',
                'fullname' => $data['fullname'],
                'mobile_number' => $data['mobile_number'],
                'email' => $data['email'],
                'country' => $data['country'],
                'note' => $data['note'] ?? null,
                'ceo' => $data['ceo'] ?? null,
                'group_name' => $data['group_name'] ?? null,
                'room_required' => $data['room_required'] ?? null,
                'additional_note' => $data['additional_note'] ?? null,
            ]);

            $ref_no = sprintf(
                "%s-%s-%04d",
                now()->format('Ymd'),
                strtoupper(substr($product->type, 0, 8)),
                $inquiry->id
            );

            $inquiry->update(['ref_no' => $ref_no]);

            if (isset($data['additional_products'])) {
                $this->createAdditionalInquiryProducts($inquiry, $data['additional_products']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "{$inquiry->product_name} inquiry has been added by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::AGENT_ADDED,
            $ipAddress
        ));

        return $inquiry;
    }

    private function createAdditionalInquiryProducts(Booking $inquiry, array $additionalProducts): void
    {
        foreach ($additionalProducts as $product) {
            $additionalProduct = Product::findOrFail($product);

            AdditionalBookingProduct::create([
                'booking_id' => $inquiry->id,
                'product_id' => $additionalProduct->id,
                'name' => $additionalProduct->name,
                'description' => $additionalProduct->description,
            ]);
        }
    }

    private function validateInquiryData(array $data)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'agent_id' => 'nullable|exists:agents,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'adult' => 'required|integer|min:1',
            'infant' => 'required|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'type' => 'required|in:booking,inquiry',
            'fullname' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:255',
            'note' => 'nullable|string',
            'additional_products' => 'nullable|array',
            'ceo' => 'nullable|string',
            'group_name' => 'required|string|max:255',
            'room_required' => 'nullable|string',
            'additional_note' => 'nullable|string',
        ];

        $messages = [
            'product_id.required' => 'A product must be selected.',
            'product_id.exists' => 'The selected product does not exist.',
            'agent_id.exists' => 'The selected agent does not exist.',
            'from_date.required' => 'The start date is required.',
            'to_date.required' => 'The end date is required.',
            'to_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'adult.required' => 'The number of adult is required.',
            'adult.min' => 'There must be at least one adult.',
            'type.required' => 'The booking type is required.',
            'type.in' => 'The booking type must be either "booking" or "inquiry".',
            'fullname.required' => 'The full name is required.',
            'mobile_number.required' => 'The mobile number is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'country.required' => 'The country is required.',
            'group_name.required' => 'Group Name is required.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

}
