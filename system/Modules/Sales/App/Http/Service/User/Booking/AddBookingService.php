<?php

namespace Modules\Sales\App\Http\Service\User\Booking;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Sales\App\Models\AdditionalBookingProduct;
use Modules\Sales\App\Models\Booking;
use Modules\SystemConfiguration\App\Models\EmailTemplate;
use Modules\User\App\Events\SendNewInquireMail;
use Modules\User\DTO\UserNewInquireDTO;

class AddBookingService
{
    public function createInquiry(array $data, $ipAddress)
    {
        $this->validateInquiryData($data);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($data['product_id']);

            $inquiry = Booking::create([
                'product_id' => $data['product_id'],
//                'user_id' => auth()->user()->id,
                'product_name' => $product->name,
                'product_type' => $product->type,
                'agent_id' => null,
                'from_date' => $data['from_date'] ?? null,
                'to_date' => $data['to_date'] ?? null,
                'adult' => $data['adult'] ?? 0,
                'children' => $data['children'] ?? 0,
                'infant' => $data['infant'] ?? 0,
                'type' => $data['type'] ?? 'inquiry',
                'status' => 'pending',
                'fullname' => $data['fullname'],
                'mobile_number' => $data['mobile_number'],
                'email' => $data['email'],
                'country' => $data['country'] ?? null,
                'note' => $data['note'] ?? null,
                'group_size' => $data['group_size'] ?? null,
                'preferred_date' => $data['preferred_date'] ?? null,
                'duration' => $data['duration'] ?? null,
                'budget_range' => $data['budget_range'] ?? null,
                'special_message' => $data['special_message'] ?? null,
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
            $this->sendEmailOnBooking($data['fullname'], $data['email']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

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
            'adult' => 'nullable|integer|min:1',
            'infant' => 'nullable|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'type' => 'required|in:booking,inquiry',
            'fullname' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'country' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'additional_products' => 'nullable|array',
            'group_size' => 'nullable|integer|min:1',
            'preferred_date' => 'nullable|date',
            'duration' => 'nullable|integer|min:1',
            'budget_range' => 'nullable|string|max:255',
            'special_message' => 'nullable|string',
        ];

        $messages = [
            'product_id.required' => 'A product must be selected.',
            'product_id.exists' => 'The selected product does not exist.',
            'agent_id.exists' => 'The selected agent does not exist.',
            'from_date.required' => 'The start date is required.',
            'to_date.required' => 'The end date is required.',
            'to_date.after_or_equal' => 'The end date must be after or equal to the start date.',
//            'adult.required' => 'The number of adult is required.',
//            'adult.min' => 'There must be at least one adult.',
            'type.required' => 'The booking type is required.',
            'type.in' => 'The booking type must be either "booking" or "inquiry".',
            'fullname.required' => 'The full name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
//            'country.required' => 'The country is required.',
//            'additional_products.*.id.required' => 'Each additional product must have an ID.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function sendEmailOnBooking($name, $email)
    {
        $template = EmailTemplate::where('name', 'new_inquire')->first();

        $message = strtr($template->message, [
            '{GUEST_NAME}' => $name,
            '{GUEST_EMAIL}' => $email,
        ]);

        $newInquire = UserNewInquireDTO::from([
            'title' => $template->title,
            'subject' => $template->subject,
            'description' => $message,
            'email' => 'info@communityhomestay.com',
        ]);

        Event::dispatch(new SendNewInquireMail($newInquire));
    }

}
