<?php

namespace Modules\Product\App\Http\Controllers\Product\Package;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Package\CreatePackageService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreatePackageController extends AdminBaseController
{

    public function __construct(private CreatePackageService $createPackageService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->createPackageService->createPackage($request->request->all(), $request->getClientIp());

        return $this->successResponse('Package has been created successfully.');
    }

}
