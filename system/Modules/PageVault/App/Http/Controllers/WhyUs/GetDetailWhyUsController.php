<?php

namespace Modules\PageVault\App\Http\Controllers\WhyUs;

use Modules\PageVault\App\Service\WhyUs\GetDetailWhyUsService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetDetailWhyUsController extends AdminBaseController
{
    public function __construct(private GetDetailWhyUsService $getDetailWhyUsService)
    {
    }

    public function __invoke($id)
    {
        $detail = $this->getDetailWhyUsService->getWhyUsDetail($id);

        return $this->successResponse('Why Us Detail has been fetched successfully.', $detail);
    }
}
