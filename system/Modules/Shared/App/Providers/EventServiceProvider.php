<?php

namespace Modules\Shared\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\App\Listeners\AdminUserActivityLogListener;
use Modules\User\App\Events\SendNewInquireMail;
use Modules\User\App\Events\SendPasswordResetLinkMail;
use Modules\User\App\Events\SendRegisterMail;
use Modules\User\App\Events\SendTripCompletedMail;
use Modules\User\App\Listeners\SendNewInquireMailFired;
use Modules\User\App\Listeners\SendPasswordResetLinkMailFired;
use Modules\User\App\Listeners\SendRegisterMailFired;
use Modules\User\App\Listeners\SendTripCompletedMailFired;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AdminUserActivityLogEvent::class => [
            AdminUserActivityLogListener::class
        ],

        SendRegisterMail::class => [
            SendRegisterMailFired::class
        ],

        SendTripCompletedMail::class => [
            SendTripCompletedMailFired::class
        ],

        SendPasswordResetLinkMail::class => [
            SendPasswordResetLinkMailFired::class
        ],

        SendNewInquireMail::class => [
            SendNewInquireMailFired::class
        ],

    ];
}
