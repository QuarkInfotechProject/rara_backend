<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Booking\UpdateBookingService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateBookingController extends AdminBaseController
{

    public function __construct(private UpdateBookingService $updateBookingService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updateBookingService->updateBooking($request->request->all(), $request->getClientIp());

        return $this->successResponse('Booking has been updated successfully.');
    }
}
