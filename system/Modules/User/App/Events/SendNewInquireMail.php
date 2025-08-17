<?php

namespace Modules\User\App\Events;

use Illuminate\Queue\SerializesModels;
use Modules\User\DTO\UserNewInquireDTO;

class SendNewInquireMail
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public UserNewInquireDTO $userNewInquireDTO)
    {
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }

}
