<?php

namespace Modules\PageVault\App\Http\Controllers\User;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\User\AddCarRentalService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class AddCarRentalController extends UserBaseController
{
    public function __construct(private AddCarRentalService $addCarRentalService) {}

    public function __invoke(Request $request)
    {
        $this->addCarRentalService->addCarRent($request->all());

        return $this->successResponse('Car rental booking has been added successfully.');
    }
}
