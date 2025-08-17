<?php

namespace Modules\Blog\App\Http\Controllers\User;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\User\GetBlogDetailService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetBlogDetailController extends UserBaseController
{

    public function __construct(private GetBlogDetailService $getBlogDetailService)
    {
    }

    public function __invoke($slug)
    {
        $data = $this->getBlogDetailService->getBlogDetailBySlug($slug);

        return $this->successResponse('Blog Detail has been fetched successfully.', $data);
    }

}
