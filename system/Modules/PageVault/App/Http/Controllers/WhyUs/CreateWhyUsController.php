<?php

namespace Modules\PageVault\App\Http\Controllers\WhyUs;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\WhyUs\AddWhyUsService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateWhyUsController extends AdminBaseController
{

    public function __construct(private AddWhyUsService $addWhyUsService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->addWhyUsService->createWhyUs($request->request->all());

        return $this->successResponse('Why Us has been added successfully.');
    }


}
