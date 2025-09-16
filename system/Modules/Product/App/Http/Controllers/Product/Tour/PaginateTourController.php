<?php

namespace Modules\Product\App\Http\Controllers\Product\Tour;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Tour\PaginateTourService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateTourController extends AdminBaseController
{

    public function __construct(private PaginateTourService $paginateTourService)
    {
    }

    public function __invoke(Request $request)
    {
        $data = $this->paginateTourService->paginate($request->get('filters'));

        return $this->successResponse('Paginated Tour has been fetched successfully.', $data);
    }

}
