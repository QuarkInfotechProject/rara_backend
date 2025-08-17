<?php

namespace Modules\PageVault\App\Http\Controllers\OurTeam;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\OurTeam\EditOurTeamService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class EditOurTeamController extends AdminBaseController
{

    public function __construct(private EditOurTeamService $editOurTeamService)
    {
    }

    public function __invoke(Request $request)
    {

        $this->editOurTeamService->updateOurTeam($request->request->all());

        return $this->successResponse('Our team has been edited successfully.');
    }
}
