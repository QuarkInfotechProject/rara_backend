<?php

namespace Modules\User\App\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\User\App\Emails\TripCompletedMail;
use Modules\User\App\Events\SendTripCompletedMail;

class SendTripCompletedMailFired
{
    /**
     * Handle the event.
     */
    public function handle(SendTripCompletedMail $event): void
    {
        $tripCompletedEmailDTO = $event->userTripCompletedDTO;

        $link = env('APP_URL') . "/profile";

        Mail::to($tripCompletedEmailDTO->email)->send(new TripCompletedMail($tripCompletedEmailDTO, $link));
    }
}
