<?php

namespace Modules\Product\App\Http\Controllers\Product\Circuit;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Circuit\UpdateCircuitService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateCircuitController extends AdminBaseController
{

    public function __construct(private UpdateCircuitService $updateCircuitService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->updateCircuitService->updateCircuit($request->request->all(), $request->getClientIp(), $request);

        return $this->successResponse('Circuit has been updated successfully.');
    }

}
