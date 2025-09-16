<?php

namespace Modules\Product\App\Http\Controllers\Product\Activities;

use Modules\Shared\App\Http\Controllers\AdminBaseController;

class FetchActivitiesDetailController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Activities\FetchActivitiesDetailService $fetchActivitiesDetailService )
    {
    }


    public function __invoke($id)
    {
        $detail = $this->fetchActivitiesDetailService->getActivitiesDetails($id);
        return $this->successResponse('Detail for activities has been fetched successfully.', $detail);
    }


}
