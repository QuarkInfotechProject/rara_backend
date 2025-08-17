<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Booking\PaginateBookingService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateBookingController extends AdminBaseController
{

    public function __construct(private PaginateBookingService $paginateBookingService)
    {
    }

    public function __invoke(Request $request)
    {
        $paginate = $this->paginateBookingService->getBookingsList($request->get('filters'));

        return $this->successResponse('Booking has been paginated successfully.', $paginate);
    }

}
