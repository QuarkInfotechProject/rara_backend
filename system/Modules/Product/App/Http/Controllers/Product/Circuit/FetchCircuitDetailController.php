<?php

namespace Modules\Product\App\Http\Controllers\Product\Circuit;

use Modules\Shared\App\Http\Controllers\AdminBaseController;

class FetchCircuitDetailController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Circuit\FetchCircuitDetailService $fetchCircuitDetailService )
    {
    }


    public function __invoke($id)
    {
        $detail = $this->fetchCircuitDetailService->getCircuitDetails($id);
        return $this->successResponse('Detail for circuit has been fetched successfully.', $detail);
    }


}
