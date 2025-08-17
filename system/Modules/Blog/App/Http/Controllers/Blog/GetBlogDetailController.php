<?php

namespace Modules\Blog\App\Http\Controllers\Blog;

use Modules\Blog\App\Service\GetBlogDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetBlogDetailController extends AdminBaseController
{

    public function __construct(private GetBlogDetailService $getBlogDetailService)
    {
    }

    public function __invoke(int $id)
    {
        $blogDetail = $this->getBlogDetailService->getBlogDetail($id);
        return $this->successResponse('Blog Detail has been fetched successfully.', $blogDetail);
    }

}
