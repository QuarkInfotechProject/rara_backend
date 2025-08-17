<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Booking\BookingChangeStatusService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ChangeBookingStatusController extends AdminBaseController
{

    public function __construct(private BookingChangeStatusService $bookingChangeStatusService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->bookingChangeStatusService->changeStatus($request->request->all(), $request->getClientIp());

        return $this->successResponse('Booking status has been changed successfully.');
    }


}
