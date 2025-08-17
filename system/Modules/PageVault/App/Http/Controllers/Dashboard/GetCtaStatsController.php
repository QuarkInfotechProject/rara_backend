<?php

namespace Modules\PageVault\App\Http\Controllers\Dashboard;

use Modules\PageVault\App\Service\Dashboard\GetCtaStatsService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetCtaStatsController extends AdminBaseController
{

    public function __construct(private GetCtaStatsService $getCtaStatsService)
    {
    }

    public function __invoke()
    {
        $data = $this->getCtaStatsService->getCtaStats();

        return $this->successResponse('Cta stats has been fetched successfully.', $data);
    }

}
