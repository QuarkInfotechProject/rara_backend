<?php

namespace Modules\PageVault\App\Http\Controllers\PageVault;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\PageVault\UpdatePageVaultService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdatePageVaultController extends AdminBaseController
{
    public function __construct(private UpdatePageVaultService $updatePageVaultService)
    {

    }

    public function __invoke(Request $request)
    {
        $this->updatePageVaultService->updatePageVault($request->request->all(), $request->getClientIp());

        return $this->successResponse('Page has been updated successfully.');
    }

}
