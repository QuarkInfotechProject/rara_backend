<?php

namespace Modules\Product\App\Http\Controllers\Product\Activities;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Activities\PaginateActivitiesService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateActivitiesController extends AdminBaseController
{

    public function __construct(private PaginateActivitiesService $paginateActivitiesService)
    {
    }

    public function __invoke(Request $request)
    {
        $data = $this->paginateActivitiesService->paginate($request->get('filters'));

        return $this->successResponse('Paginated activities has been fetched successfully.', $data);
    }

}
