<?php

namespace Modules\PageVault\App\Service\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\CarRental;
use Modules\SystemConfiguration\App\Models\EmailTemplate;
use Modules\User\App\Events\SendNewInquireMail;
use Modules\User\DTO\UserNewInquireDTO;

class AddCarRentalService
{
    public function addCarRent(array $data)
    {
        try {
            DB::beginTransaction();

            $validatedData = Validator::make($data, [
                'user_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'contact' => 'required|string|max:255',
                'max_people' => 'nullable|integer',
                'pickup_address' => 'required|string|max:255',
                'destination_address' => 'required|string|max:255',
                'pickup_time' => 'required|date',
                'message' => 'nullable|string',
                'car_type' => 'required|string|max:255',
            ])->validate();

            $carRentalData = [
                'user_name' => $validatedData['user_name'],
                'email' => $validatedData['email'],
                'contact' => $validatedData['contact'],
                'max_people' => $validatedData['max_people'] ?? null,
                'pickup_address' => $validatedData['pickup_address'],
                'destination_address' => $validatedData['destination_address'],
                'pickup_time' => $validatedData['pickup_time'],
                'message' => $validatedData['message'] ?? null,
                'type' => $validatedData['car_type'],
                'status' => 'new',
            ];

            CarRental::create($carRentalData);

            DB::commit();

            // Optional: send email
            // $this->sendEmailOnBooking($carRentalData['user_name'], $carRentalData['email']);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function sendEmailOnBooking(string $name, string $email)
    {
        $template = EmailTemplate::where('name', 'new_inquire_cta')->first();

        if (!$template) return;

        $message = strtr($template->message, [
            '{GUEST_NAME}' => $name,
            '{GUEST_EMAIL}' => $email,
        ]);

        $newInquire = UserNewInquireDTO::from([
            'title' => $template->title,
            'subject' => $template->subject,
            'description' => $message,
            'email' => $email,
        ]);

        Event::dispatch(new SendNewInquireMail($newInquire));
    }
}
