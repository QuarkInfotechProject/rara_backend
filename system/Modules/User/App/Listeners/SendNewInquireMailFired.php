<?php

namespace Modules\User\App\Listeners;

use Modules\User\App\Emails\UserNewInquireMail;
use Modules\User\App\Events\SendNewInquireMail;
use Illuminate\Support\Facades\Mail;

class SendNewInquireMailFired
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
    public function handle(SendNewInquireMail $event): void
    {
        $data = $event->userNewInquireDTO;

        Mail::to($data->email)->send(new UserNewInquireMail($data));
    }
}
