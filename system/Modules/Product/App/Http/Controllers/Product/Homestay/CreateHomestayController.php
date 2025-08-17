<?php

namespace Modules\Product\App\Http\Controllers\Product\Homestay;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateHomestayController extends AdminBaseController
{
    public function __construct(private \Modules\Product\App\Service\Admin\Product\Homestay\CreateHomestayService $createHomestayService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createHomestayService->createHomestay($request->request->all(), $request->getClientIp());

       return $this->successResponse('Homestay Has been created successfully.');
    }

}
