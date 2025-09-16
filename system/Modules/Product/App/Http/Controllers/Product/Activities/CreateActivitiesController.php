<?php

namespace Modules\Product\App\Http\Controllers\Product\Activities;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateActivitiesController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Activities\CreateActivitiesService $createActivitiesService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createActivitiesService->createActivities($request->all(), $request->getClientIp(), $request);
        return $this->successResponse('Activities has been created successfully.');
    }


}
