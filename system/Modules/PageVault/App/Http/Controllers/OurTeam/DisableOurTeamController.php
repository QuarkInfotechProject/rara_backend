<?php

namespace Modules\PageVault\App\Http\Controllers\OurTeam;

use Modules\PageVault\App\Service\OurTeam\DisableOurTeamService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DisableOurTeamController extends AdminBaseController
{

    public function __construct(private DisableOurTeamService $disableOurTeamService)
    {
    }

    public function __invoke($id)
    {
        $this->disableOurTeamService->disableTeam($id);

        return $this->successResponse('Team member has been disabled successfully.');
    }
}
