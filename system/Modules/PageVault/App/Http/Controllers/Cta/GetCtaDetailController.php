<?php

namespace Modules\PageVault\App\Http\Controllers\Cta;

use Modules\PageVault\App\Service\Cta\GetCTADetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetCtaDetailController extends AdminBaseController
{

    public function __construct(private GetCTADetailService $getCTADetailService)
    {
    }

    public function __invoke($id)
    {
        $detail = $this->getCTADetailService->getDetailByIdService($id);
        return $this->successResponse('Cta detail has been fetched successfully.', $detail);
    }
}
