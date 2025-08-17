<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Modules\Sales\App\Http\Service\Admin\Booking\ToggleHasRespondedService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ToggleHasRespondedController extends AdminBaseController
{

    public function __construct(private ToggleHasRespondedService $toggleHasRespondedService)
    {
    }


    public function __invoke($bookingId)
    {
        $this->toggleHasRespondedService->toggleHasResponded($bookingId);
        return $this->successResponse('Has Responded Toggled successfully.');
    }

}
