<?php

namespace Modules\Blog\App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\TrashBlogService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class TrashBlogController extends AdminBaseController
{

    public function __construct(private TrashBlogService $softDeleteBlogService)
    {

    }

    public function __invoke(Request $request)
    {
        $this->softDeleteBlogService->trashBlog($request->get('id'), $request->getClientIp());
        return $this->successResponse('Blog has been trashed successfully.');
    }

}
