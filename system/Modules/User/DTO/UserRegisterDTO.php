<?php

namespace Modules\User\DTO;

use Modules\Shared\DTO\Constructor;

class UserRegisterDTO extends Constructor
{
    public string $email;
    public int $verificationCode;
    public string $fullName;

    public string $password;
}
