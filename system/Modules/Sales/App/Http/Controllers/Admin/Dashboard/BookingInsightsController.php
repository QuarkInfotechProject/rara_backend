<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Dashboard\BookingInsightsService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class BookingInsightsController extends AdminBaseController
{

    public function __construct(private BookingInsightsService $bookingInsightsService)
    {
    }

    public function __invoke(Request $request)
    {
        $data = $this->bookingInsightsService->getInsights($request->get('startDate'), $request->get('endDate'));

        return $this->successResponse('Booking Insights has been fetched successfully.', $data);
    }
}
