<?php

namespace Modules\Blog\App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\UpdateBlogService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateBlogController extends AdminBaseController
{

    public function __construct(private UpdateBlogService $updateBlogService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updateBlogService->updateBlog($request->request->all(), $request->getClientIp());

        return $this->successResponse('Blog has been updated successfully.');
    }


}
