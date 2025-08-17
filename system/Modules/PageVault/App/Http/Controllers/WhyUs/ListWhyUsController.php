<?php

namespace Modules\PageVault\App\Http\Controllers\WhyUs;

use Modules\PageVault\App\Service\WhyUs\ListWhyUsService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListWhyUsController extends AdminBaseController
{
    public function __construct(private ListWhyUsService $listWhyUsService)
    {
    }

    public function __invoke()
    {
        $whyUsList = $this->listWhyUsService->getWhyUsList();
        return $this->successResponse('Why Us list has been fetched successfully.', $whyUsList);
    }
}
