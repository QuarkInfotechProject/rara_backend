<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Modules\Sales\App\Http\Service\Admin\Booking\GetBookingDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetBookingDetailController extends AdminBaseController
{

    public function __construct(private GetBookingDetailService $getBookingDetailService)
    {
    }

    public function __invoke($id)
    {
        $detail = $this->getBookingDetailService->getBookingDetail($id);

        return $this->successResponse('Booking detail has been fetched successfully.', $detail);
    }

}
