<?php

namespace Modules\Product\App\Http\Controllers\Product;

use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListRelatedBlogForSelectController extends AdminBaseController
{
    public function __construct(private \Modules\Product\App\Service\Admin\Product\ListRelatedBlogForSelectService $listRelatedBlogForSelectService)
    {
    }

    public function __invoke()
    {
        $relatedBlog = $this->listRelatedBlogForSelectService->getPublishedBlogsForSelect();

        return $this->successResponse('Related blog has been fetched successfully.', $relatedBlog);
    }
}
