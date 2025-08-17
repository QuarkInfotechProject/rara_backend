<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\Event;
use Modules\SystemConfiguration\App\Models\EmailTemplate;
use Modules\SystemConfiguration\App\Models\SystemConfig;
use Modules\User\App\Events\SendRegisterMail;
use Modules\User\App\Models\User;
use Modules\User\App\Models\VerificationCode;
use Modules\User\DTO\SendRegisterEmailDTO;

class UserSendRegisterMailService
{
    function sendRegisterMail(string $email)
    {
        if (User::where('email', $email)->exists()) {
            throw new \Exception('This email is already registered.');
        }
        $result = $this->saveVerificationCode($email);

        $template = EmailTemplate::where('name', 'user_registration')->first();

        $message = strtr($template->message, [
            '{CODE}' => $result->code
        ]);

        $sendRegisterEmailDTO = SendRegisterEmailDTO::from([
            'system' => $template->name,
            'title' => $template->title,
            'subject' => $template->subject,
            'image' => $template->image,
            'description' => $message,
            'email' => $email
        ]);

        Event::Dispatch(new SendRegisterMail($sendRegisterEmailDTO));
    }

    public function saveVerificationCode($email)
    {
        $minutes = SystemConfig::where('name', 'code_expiration_time')->pluck('value')->first();

        return VerificationCode::updateOrCreate([
            'email' => $email,
            'code' => mt_rand(100000, 999999),
            'expires_at' => now()->addMinutes($minutes),
        ]);
    }
}
