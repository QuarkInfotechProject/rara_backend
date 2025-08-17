<?php

namespace Modules\Blog\App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Modules\Blog\App\Service\RestoreBlogService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class RestoreBlogController extends AdminBaseController
{

    public function __construct(private RestoreBlogService $restoreBlogService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->restoreBlogService->restoreBlog($request->get('id'), $request->getClientIp());

        return $this->successResponse('Blog has been restored successfully.');
    }
}
