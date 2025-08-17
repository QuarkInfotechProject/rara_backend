<?php

namespace Modules\Product\App\Http\Controllers\Product\Package;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Modules\Product\App\Service\Admin\Product\Package\PaginatePackageService;

class PaginatePackageController extends AdminBaseController
{

    public function __construct(private PaginatePackageService $packageService)
    {
    }


    public function __invoke(Request $request)
    {
       $paginate = $this->packageService->paginate($request->get('filters'));

        return $this->successResponse('Package has been paginated successfully.', $paginate);
    }


}
