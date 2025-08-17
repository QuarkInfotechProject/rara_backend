<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Modules\Sales\App\Http\Service\Admin\Booking\ListAllProductForSelectService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListProductForSelectController extends AdminBaseController
{

    public function __construct(private ListAllProductForSelectService $listAllProductForSelectService)
    {
    }

    public function __invoke()
    {
        $list = $this->listAllProductForSelectService->getAllProductListForSelect();

        return $this->successResponse('Product list has been fetched successfully.', $list);
    }


}
