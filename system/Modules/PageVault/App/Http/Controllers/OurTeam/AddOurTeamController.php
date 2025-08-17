<?php

namespace Modules\PageVault\App\Http\Controllers\OurTeam;

use Modules\PageVault\App\Service\OurTeam\AddOurTeamService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Symfony\Component\HttpFoundation\Request;

class AddOurTeamController extends AdminBaseController
{
    public function __construct(private AddOurTeamService $addOurTeamService)
    {
    }

    public function __invoke(Request $request)
    {

        $this->addOurTeamService->addOurTeam($request->request->all());


        return $this->successResponse('Our team has been added successfully.');
    }

}
