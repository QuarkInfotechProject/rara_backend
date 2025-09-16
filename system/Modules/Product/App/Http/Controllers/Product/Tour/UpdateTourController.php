<?php

namespace Modules\Product\App\Http\Controllers\Product\Tour;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Tour\UpdateTourService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateTourController extends AdminBaseController
{

    public function __construct(private UpdateTourService $updateTourService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->updateTourService->updateTour($request->request->all(), $request->getClientIp(), $request);

        return $this->successResponse('Tour has been updated successfully.');
    }

}
