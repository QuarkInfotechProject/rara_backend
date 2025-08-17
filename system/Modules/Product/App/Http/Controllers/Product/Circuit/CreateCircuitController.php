<?php

namespace Modules\Product\App\Http\Controllers\Product\Circuit;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateCircuitController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Circuit\CreateCircuitService $createCircuitService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createCircuitService->createCircuit($request->all(), $request->getClientIp(), $request);
        return $this->successResponse('Circuit has been created successfully.');
    }


}
