<?php

namespace Modules\PageVault\App\Http\Controllers\OurTeam;

use Modules\PageVault\App\Service\OurTeam\EnableOurTeamService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class EnableOurTeamController extends AdminBaseController
{

    public function __construct(private EnableOurTeamService $enableOurTeamService)
    {
    }

    public function __invoke(int $id)
    {

        $this->enableOurTeamService->enableTeam($id);

        return $this->successResponse('Our team has been enabled successfully.');
    }
}
