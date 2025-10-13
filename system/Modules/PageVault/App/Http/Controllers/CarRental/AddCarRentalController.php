<?php

namespace Modules\PageVault\App\Http\Controllers\CarRental;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\CarRental\AddCarRentalService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class AddCarRentalController extends AdminBaseController
{

    public function __construct(private AddCarRentalService $addCarRentalService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->addCarRentalService->addCarRental($request->request->all());
        return $this->successResponse('Cta has been added successfully.');
    }

}
