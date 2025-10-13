<?php

namespace Modules\PageVault\App\Http\Controllers\CarRental;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\CarRental\UpdateCarRentalService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateCarRentalController extends AdminBaseController
{

    public function __construct(private UpdateCarRentalService $updateCarRentalService)
    {
    }

    public function __invoke(Request $request)
    {
        $updatedCarRental = $this->updateCarRentalService->updateCarRental($request->all());

        return $this->successResponse('Car rental has been updated successfully.', $updatedCarRental);
    }


}
