<?php

namespace Modules\PageVault\App\Http\Controllers\User;

use Modules\PageVault\App\Service\User\GetPageDetailService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetPagesDetailController extends UserBaseController
{

    public function __construct(private GetPageDetailService $getPageDetailService)
    {
    }

    public function __invoke($slug)
    {
        $data = $this->getPageDetailService->getPageVaultData($slug);

        return $this->successResponse('Page detail has been fetched successfully.', $data);
    }

}
