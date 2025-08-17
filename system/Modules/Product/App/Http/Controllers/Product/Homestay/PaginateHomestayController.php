<?php

namespace Modules\Product\App\Http\Controllers\Product\Homestay;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Product\Homestay\PaginateHomestayService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateHomestayController extends AdminBaseController
{
    public function __construct(private PaginateHomestayService $paginateHomestayService)
    {
    }

    public function __invoke(Request $request)
    {
       $paginate = $this->paginateHomestayService->paginate($request->get('filters'));

        return $this->successResponse('Homestay list has been fetched successfully.', $paginate);
    }
}
