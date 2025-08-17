<?php

namespace Modules\PageVault\App\Http\Controllers\WhyUs;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\WhyUs\EditWhyUsService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateWhyUsController extends AdminBaseController
{
    public function __construct(private EditWhyUsService $editWhyUsService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->editWhyUsService->editWhyUs($request->request->all());
        return $this->successResponse('Why Us has been updated successfully.');
    }
}
