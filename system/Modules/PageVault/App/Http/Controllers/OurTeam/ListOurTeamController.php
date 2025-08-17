<?php

namespace Modules\PageVault\App\Http\Controllers\OurTeam;

use Modules\PageVault\App\Service\OurTeam\ListOurTeamService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListOurTeamController extends AdminBaseController
{

    public function __construct(private ListOurTeamService $listOurTeamService)
    {
    }

    public function __invoke()
    {
        $list = $this->listOurTeamService->listOurTeam();

        return $this->successResponse('Our team list has been fetched successfully.', $list);
    }
}
