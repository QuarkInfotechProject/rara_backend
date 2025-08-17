<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Dashboard\GetBookingByStatusService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetBookingByStatusController extends AdminBaseController
{

    public function __construct(private GetBookingByStatusService  $getBookingByStatusService)
    {
    }

    public function __invoke(Request $request)
    {
        $data = $this->getBookingByStatusService->getBookingsByStatus($request->get('status'), $request->get('startDate'), $request->get('endDate'));
        return $this->successResponse('Data fetched successfully.', $data);
    }
}
