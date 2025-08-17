<?php

namespace Modules\PageVault\App\Http\Controllers\OurTeam;

use Modules\PageVault\App\Service\OurTeam\GetDetailOurTeamService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetDetailOurTeamController extends AdminBaseController
{

    public function __construct(private GetDetailOurTeamService $getDetailOurTeamService)
    {
    }

    public function __invoke($id)
    {

       $detail = $this->getDetailOurTeamService->getOurTeamDetail($id);
        return $this->successResponse('Our team detail has been fetched successfully.', $detail);
    }
}
