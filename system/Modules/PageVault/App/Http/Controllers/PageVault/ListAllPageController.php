<?php

namespace Modules\PageVault\App\Http\Controllers\PageVault;

use Modules\PageVault\App\Service\PageVault\ListAllPageService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListAllPageController extends AdminBaseController
{

    public function __construct(private ListAllPageService $listAllPageService)
    {
    }


    public function __invoke()
    {
        $pages = $this->listAllPageService->listAllPages();

        return $this->successResponse('Pages list has been fetched successfully.', $pages);
    }

}
