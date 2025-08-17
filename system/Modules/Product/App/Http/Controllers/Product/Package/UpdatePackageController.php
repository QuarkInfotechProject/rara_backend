<?php

namespace Modules\Product\App\Http\Controllers\Product\Package;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Package\UpdatePackageService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdatePackageController extends AdminBaseController
{

    public function __construct(private UpdatePackageService $updatePackageService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updatePackageService->updatePackage($request->request->all(), $request->getClientIp());

        return $this->successResponse('Package has been updated successfully.');
    }


}
