<?php

namespace Modules\Product\App\Http\Controllers\Product\Circuit;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Circuit\PaginateCircuitService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateCircuitController extends AdminBaseController
{

    public function __construct(private PaginateCircuitService $paginateCircuitService)
    {
    }

    public function __invoke(Request $request)
    {
        $data = $this->paginateCircuitService->paginate($request->get('filters'));

        return $this->successResponse('Paginated Trek has been fetched successfully.', $data);
    }

}
