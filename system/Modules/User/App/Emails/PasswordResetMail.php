<?php

namespace Modules\User\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\DTO\UserForgotPasswordDTO;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $url;

    /**
     * Create a new message instance.
     */
    public function __construct(UserForgotPasswordDTO $data, $passwordResetUrl)
    {
        $this->data = $data;
        $this->passwordResetUrl = $passwordResetUrl;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('user::reset_password', ['data' => $this->data, 'passwordResetUrl' => $this->passwordResetUrl]);
    }
}
