<?php

namespace Modules\PageVault\App\Http\Controllers\CarRental;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\CarRental\ChangeStatusCarRentalService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ChangeCarRentalStatusController extends AdminBaseController
{

    public function __construct(private ChangeStatusCarRentalService $changeStatusCarRentalService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->changeStatusCarRentalService->changeStatus($request->request->all());
        return $this->successResponse('Car Rental status has been changed successfully.');
    }
}
