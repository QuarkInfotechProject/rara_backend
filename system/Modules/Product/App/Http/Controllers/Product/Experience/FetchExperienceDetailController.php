<?php

namespace Modules\Product\App\Http\Controllers\Product\Experience;

use Modules\Product\App\Service\Admin\Product\Experience\FetchExperienceDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class FetchExperienceDetailController extends AdminBaseController
{

    public function __construct(private FetchExperienceDetailService $fetchExperienceDetailService)
    {
    }

    public function __invoke($id)
    {
        $experienceDetail = $this->fetchExperienceDetailService->getExperienceDetails($id);

        return $this->successResponse('Experience Detail has been fetched successfully.', $experienceDetail );
    }

}
