<?php

namespace Modules\Blog\App\Http\Controllers\User;

use Modules\Blog\App\Service\User\ListAllBlogSlugService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListAllBlogSlugController extends UserBaseController
{

    public function __construct(private ListAllBlogSlugService $listAllBlogSlugService)
    {
    }

    public function __invoke()
    {
        $data = $this->listAllBlogSlugService->getAllSlugs();

        return $this->successResponse('All blog slugs fetched successfully.', $data);
    }

}
