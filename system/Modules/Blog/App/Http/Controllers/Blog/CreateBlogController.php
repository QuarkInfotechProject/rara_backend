<?php

namespace Modules\Blog\App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\CreateBlogService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateBlogController extends AdminBaseController
{


    public function __construct(private CreateBlogService $createBlogService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createBlogService->createBlog($request->request->all(), $request->getClientIp());
        return $this->successResponse('New blog has been created successfully.');
    }
}
