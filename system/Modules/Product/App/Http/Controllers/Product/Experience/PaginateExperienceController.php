<?php

namespace Modules\Product\App\Http\Controllers\Product\Experience;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateExperienceController extends AdminBaseController
{
    public function __construct(private \Modules\Product\App\Service\Admin\Product\Experience\PaginateExperienceService $paginateExperienceService)
    {
    }


    public function __invoke(Request $request)
    {
        $detail = $this->paginateExperienceService->paginate($request->get('filters'));

        return $this->successResponse('Experience has been fetched successfully.', $detail);
    }

}
