<?php

namespace Modules\Blog\App\Http\Controllers\User;

use Modules\Blog\App\Service\User\ListMediaCoverageForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListMediaCoverageForHomepageController extends UserBaseController
{

    public function __construct(private ListMediaCoverageForHomepageService $listMediaCoverageService)
    {
    }

    public function __invoke()
    {
        $data = $this->listMediaCoverageService->execute();
        return $this->successResponse('Media Coverage List has been fetched successfully.', $data);
    }

}
