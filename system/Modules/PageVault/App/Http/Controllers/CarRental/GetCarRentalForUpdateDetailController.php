<?php

namespace Modules\PageVault\App\Http\Controllers\CarRental;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\CarRental\GetCarRentalForUpdateDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetCarRentalForUpdateDetailController extends AdminBaseController
{
    public function __construct(private GetCarRentalForUpdateDetailService $getCarRentalForUpdateDetailService)
    {
    }

    public function __invoke($id)
    {
        $carRentalDetail = $this->getCarRentalForUpdateDetailService->getCarRentalDetail($id);

        return $this->successResponse('Car rental detail fetched successfully.', $carRentalDetail);
    }
}
