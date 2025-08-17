<?php

namespace Modules\PageVault\App\Http\Controllers\User;

use Modules\PageVault\App\Service\User\GetTeamListService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetTeamListController extends UserBaseController
{
    public function __construct(private GetTeamListService $getTeamListService)
    {
    }

    public function __invoke()
    {
        $data = $this->getTeamListService->execute();
        return $this->successResponse('Team List has been fetched successfully.', $data);
    }

}
