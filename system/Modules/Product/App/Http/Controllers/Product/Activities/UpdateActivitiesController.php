<?php

namespace Modules\Product\App\Http\Controllers\Product\Activities;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Activities\UpdateActivitiesService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateActivitiesController extends AdminBaseController
{

    public function __construct(private UpdateActivitiesService $updateTActivitiesService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->updateTActivitiesService->updateActivities($request->request->all(), $request->getClientIp(), $request);

        return $this->successResponse('Activities has been updated successfully.');
    }

}
