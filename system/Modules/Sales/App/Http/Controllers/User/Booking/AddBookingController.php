<?php

namespace Modules\Sales\App\Http\Controllers\User\Booking;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\User\Booking\AddBookingService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class AddBookingController extends UserBaseController
{

    public function __construct(private AddBookingService $addBookingService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->addBookingService->createInquiry($request->request->all(), $request->getClientIp());

        return $this->successResponse('Inquiry has been requested.');
    }
}
