<?php

namespace Modules\PageVault\App\Http\Controllers\CarRental;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\CarRental\PaginateCarRentalService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateCarRentalController extends AdminBaseController
{
    public function __construct(private PaginateCarRentalService $paginateCarRentalService)
    {
    }

    public function __invoke(Request $request)
    {
        $paginate = $this->paginateCarRentalService->paginateCarRental($request->get('filters'));
        return $this->successResponse('Paginated list has been fetched successfully.', $paginate);
    }
}
