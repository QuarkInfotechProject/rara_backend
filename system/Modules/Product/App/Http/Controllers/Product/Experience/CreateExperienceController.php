<?php

namespace Modules\Product\App\Http\Controllers\Product\Experience;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateExperienceController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Experience\CreateExperienceService $createExperienceService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createExperienceService->createExperience($request->request->all(), $request->getClientIp());

        return $this->successResponse('Experience Has been created successfully.');
    }

}
