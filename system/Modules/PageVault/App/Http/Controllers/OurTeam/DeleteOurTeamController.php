<?php

namespace Modules\PageVault\App\Http\Controllers\OurTeam;

use Modules\PageVault\App\Service\OurTeam\DeleteOurTeamService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DeleteOurTeamController extends AdminBaseController
{
    public function __construct(private DeleteOurTeamService $deleteOurTeamService)
    {
    }

    public function __invoke(int $id)
    {
        $this->deleteOurTeamService->deleteTeam($id);

        return $this->successResponse('Team member has been deleted successfully.');
    }

}
