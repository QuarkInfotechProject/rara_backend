<?php

namespace Modules\Product\App\Http\Controllers\Product\Tour;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateTourController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Tour\CreateTourService $createTourService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createTourService->createTour($request->all(), $request->getClientIp(), $request);
        return $this->successResponse('Tour has been created successfully.');
    }


}
