<?php

namespace Modules\Blog\App\Http\Controllers\User;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\User\PaginateBlogsService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListBlogController extends UserBaseController
{

    public function __construct(private PaginateBlogsService $listBlogsService)
    {
    }

    public function __invoke(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $data = $this->listBlogsService->getPaginatedBlogs($request->get('filters'), $perPage);
        return $this->successResponse('Blogs has been fetched successfully.', $data);
    }

}
