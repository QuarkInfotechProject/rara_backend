<?php

namespace Modules\PageVault\App\Service\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\Cta;
use Modules\SystemConfiguration\App\Models\EmailTemplate;
use Modules\User\App\Events\SendNewInquireMail;
use Modules\User\DTO\UserNewInquireDTO;

class AddCTAService
{

    public function addCta($data)
    {
        try {
            DB::beginTransaction();

            $validatedData = Validator::make($data, [
                'fullname' => 'required|string',
                'email' => 'required|string|max:255',
                'phone_number' => 'string',
                'description' => 'string',
                'type' => 'required|in:contact,partner',
            ])->validate();

            $validatedData = [
                "fullname" => $validatedData['fullname'],
                "email" => $validatedData['email'],
                "description" => $validatedData['description'],
                "phone_number" => $validatedData['phone_number'],
                "type" => $validatedData['type'],
                "status" => 'new',
            ];

            Cta::create($validatedData);

            DB::commit();

//            $this->sendEmailOnBooking($validatedData['fullname'], $validatedData['email']);

        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    private function sendEmailOnBooking($name, $email)
    {
        $template = EmailTemplate::where('name', 'new_inquire_cta')->first();

        $message = strtr($template->message, [
            '{GUEST_NAME}' => $name,
            '{GUEST_EMAIL}' => $email,
        ]);

        $newInquire = UserNewInquireDTO::from([
            'title' => $template->title,
            'subject' => $template->subject,
            'description' => $message,
            'email' => 'urmistha705@gmail.com',
        ]);

        Event::dispatch(new SendNewInquireMail($newInquire));
    }

}
