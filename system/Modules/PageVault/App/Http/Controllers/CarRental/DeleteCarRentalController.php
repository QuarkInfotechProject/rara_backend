<?php

namespace Modules\PageVault\App\Http\Controllers\CarRental;

use Modules\PageVault\App\Service\CarRental\DeleteCarRentalService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DeleteCarRentalController extends AdminBaseController
{

    public function __construct(private DeleteCarRentalService $deleteCarRentalService)
    {
    }

    public function __invoke($id)
    {
        $this->deleteCarRentalService->deleteCarRental($id);
        return $this->successResponse('Car Rental has been deleted successfully.');
    }
}
