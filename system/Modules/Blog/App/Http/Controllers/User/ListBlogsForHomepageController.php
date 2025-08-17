<?php

namespace Modules\Blog\App\Http\Controllers\User;

use Modules\Blog\App\Service\User\ListBlogsForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListBlogsForHomepageController extends UserBaseController
{
    public function __construct(private ListBlogsForHomepageService $listBlogsForHomepageService)
    {
    }


    public function __invoke()
    {
        $blog = $this->listBlogsForHomepageService->execute();
        return $this->successResponse('List has been fetched successfully.', $blog);
    }


}
