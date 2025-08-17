<?php

namespace Modules\Product\App\Http\Controllers\Product\Homestay;

use Modules\Product\App\Service\Admin\Product\Homestay\FetchHomestayDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class FetchHomestayDetailController extends AdminBaseController
{

    public function __construct(private FetchHomestayDetailService $fetchHomestayDetailService)
    {
    }


    public function __invoke($id)
    {
        $detail = $this->fetchHomestayDetailService->getHomestayDetails($id);

        return $this->successResponse('Fetched', $detail);
    }

}
