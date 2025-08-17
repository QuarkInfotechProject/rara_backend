<?php

namespace Modules\Product\App\Http\Controllers\Product\Package;

use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetPackageDetailController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Package\GetPackageDetailService $getPackageDetailService)
    {
    }


    public function __invoke($id)
    {
        $data = $this->getPackageDetailService->getPackageDetails($id);

        return $this->successResponse('Package detail has been fetched successfully.', $data);
    }

}
