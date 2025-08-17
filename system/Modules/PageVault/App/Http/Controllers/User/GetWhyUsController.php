<?php

namespace Modules\PageVault\App\Http\Controllers\User;

use Modules\PageVault\App\Service\User\ListWhyUsService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetWhyUsController extends UserBaseController
{
    public function __construct(private ListWhyUsService $getWhyUsService)
    {
    }

    public function __invoke()
    {
        $data = $this->getWhyUsService->execute();
        return $this->successResponse('Why Us has been fetched successfully.', $data);
    }

}
