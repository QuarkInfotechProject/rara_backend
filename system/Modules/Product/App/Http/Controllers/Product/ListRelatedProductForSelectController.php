<?php

namespace Modules\Product\App\Http\Controllers\Product;

use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListRelatedProductForSelectController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\ListRelatedProductForSelectService $relatedProductForSelectService)
    {
    }

    public function __invoke($type)
    {
        $relatedBlog = $this->relatedProductForSelectService->getRelatedHomestaysForSelect($type);

        return $this->successResponse('Related homestay has been fetched successfully.', $relatedBlog);
    }
}
