<?php

namespace Modules\Product\App\Http\Controllers\Product\Homestay;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateHomestayController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Homestay\UpdateHomestayService $updateHomestayService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->updateHomestayService->updateHomestay($request->request->all(), $request->getClientIp());

        return $this->successResponse('Homestay has been updated successfully.');
    }



}
