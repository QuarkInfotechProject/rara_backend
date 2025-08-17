<?php

namespace Modules\Product\App\Http\Controllers\Product\Experience;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Experience\UpdateExperienceService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateExperienceController extends AdminBaseController
{

    public function __construct(private UpdateExperienceService $updateExperienceService)
    {
    }

    public function __invoke(Request $request)
    {

        $this->updateExperienceService->updateExperience($request->request->all(), $request->getClientIp());

        return $this->successResponse('Experience has been updated successfully.');
    }


}
