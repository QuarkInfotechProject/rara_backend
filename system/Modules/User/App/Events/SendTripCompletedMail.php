<?php

namespace Modules\User\App\Events;

use Illuminate\Queue\SerializesModels;
use Modules\User\DTO\SendRegisterEmailDTO;
use Modules\User\DTO\UserTripCompletedDTO;

class SendTripCompletedMail
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public UserTripCompletedDTO $userTripCompletedDTO)
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
