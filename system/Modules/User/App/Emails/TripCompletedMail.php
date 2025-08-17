<?php

namespace Modules\User\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\User\DTO\UserTripCompletedDTO;

class TripCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $link;

    /**
     * Create a new message instance.
     */
    public function __construct(UserTripCompletedDTO $data, $link)
    {
        $this->data = $data;
        $this->link = $link;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('user::trip_completed', ['data' => $this->data, 'link' => $this->link]);
    }
}
