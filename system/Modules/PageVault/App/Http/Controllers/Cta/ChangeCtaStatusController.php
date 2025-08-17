<?php

namespace Modules\PageVault\App\Http\Controllers\Cta;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\Cta\ChangeStatusCtaService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ChangeCtaStatusController extends AdminBaseController
{

    public function __construct(private ChangeStatusCtaService $changeStatusCtaService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->changeStatusCtaService->changeStatus($request->request->all());
        return $this->successResponse();
    }
}
