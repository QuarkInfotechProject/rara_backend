<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Booking\AddInquiryService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class AddBookingController extends AdminBaseController
{

    public function __construct(private AddInquiryService $addBookingService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->addBookingService->createInquiry($request->request->all(), $request->getClientIp());

        return $this->successResponse('Inquiry has been added successfully.');
    }


}
