<?php

namespace Modules\User\DTO;

use Modules\Shared\DTO\Constructor;

class UserTripCompletedDTO extends Constructor
{

    public string $title;
    public string $subject;
    public string $description;
    public string $email;

}
