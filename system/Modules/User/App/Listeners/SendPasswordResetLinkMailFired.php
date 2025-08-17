<?php

namespace Modules\User\App\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\User\App\Emails\PasswordResetMail;
use Modules\User\App\Events\SendPasswordResetLinkMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordResetLinkMailFired
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendPasswordResetLinkMail $event): void
    {
        $data = $event->userForgotPasswordDTO;
        $passwordResetUrl = env('APP_URL') . "/reset-password/{$data->token}";

        Mail::to($data->email)->send(new PasswordResetMail($data, $passwordResetUrl));
    }
}
