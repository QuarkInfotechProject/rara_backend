<?php

namespace Modules\Blog\App\Http\Controllers\User;

use Modules\Blog\App\Service\User\ListMediaCoverageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListMediaCoverageController extends UserBaseController
{

    public function __construct(private ListMediaCoverageService $listMediaCoverageService)
    {
    }

    public function __invoke()
    {
        $data = $this->listMediaCoverageService->execute();
        return $this->successResponse('Media Coverage List has been fetched successfully.', $data);
    }

}
