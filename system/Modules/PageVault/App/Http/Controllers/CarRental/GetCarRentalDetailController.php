<?php

namespace Modules\PageVault\App\Http\Controllers\CarRental;

use Modules\PageVault\App\Service\CarRental\GetCarRentalDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetCarRentalDetailController extends AdminBaseController
{

    public function __construct(private GetCarRentalDetailService $getCarRentalDetailService)
    {
    }

    public function __invoke($id)
    {
        $detail = $this->getCarRentalDetailService->getDetailByIdService($id);
        return $this->successResponse('Car rental detail has been fetched successfully.', $detail);
    }
}
