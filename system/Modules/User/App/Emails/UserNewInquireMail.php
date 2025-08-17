<?php

namespace Modules\User\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\User\DTO\UserNewInquireDTO;

class UserNewInquireMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $url;

    /**
     * Create a new message instance.
     */
    public function __construct(UserNewInquireDTO $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('user::new_inquire_mail', ['data' => $this->data]);
    }
}
