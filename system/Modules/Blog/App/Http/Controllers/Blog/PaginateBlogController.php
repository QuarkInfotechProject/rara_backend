<?php

namespace Modules\Blog\App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\PaginateBlogService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateBlogController extends AdminBaseController
{

    public function __construct(private PaginateBlogService $paginateBlogService)
    {
    }

    public function __invoke(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $blogList = $this->paginateBlogService->paginateBlog($request->get('filters'), $perPage);
        return $this->successResponse('Paginated blog has been fetched successfully.', $blogList);
    }

}
