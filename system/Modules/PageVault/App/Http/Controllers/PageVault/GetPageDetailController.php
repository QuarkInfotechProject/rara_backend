<?php

namespace Modules\PageVault\App\Http\Controllers\PageVault;

use Modules\PageVault\App\Service\PageVault\GetPageDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetPageDetailController extends AdminBaseController
{
    public function __construct(private GetPageDetailService $getPageDetailService)
    {
    }

    public function __invoke(string $type)
    {
        $pageDetail = $this->getPageDetailService->getPageDetail($type);

        return $this->successResponse('Page detail has been fetched successfully.', $pageDetail);
    }

}
