<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Modules\Sales\App\Http\Service\Admin\Booking\GetBookingDetailForUpdateService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetBookingForUpdateDetailController extends AdminBaseController
{

    public function __construct(private GetBookingDetailForUpdateService $getBookingDetailForUpdateService)
    {
    }

    public function __invoke($id)
    {
        $detail = $this->getBookingDetailForUpdateService->getBookingDetail($id);

        return $this->successResponse('Booking Detail has been fetched successfully.', $detail);
    }

}
