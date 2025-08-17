<?php

namespace Modules\Product\App\Http\Controllers\Product;

use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListTagForSelectController extends AdminBaseController
{
    public function __construct(private \Modules\Product\App\Service\Admin\Product\ListTagForSelectService $listTagForSelectService)
    {
    }

    public function __invoke()
    {
        $tagList = $this->listTagForSelectService->getTagsForSelect();

        return $this->successResponse('Tag list has been fetched successfully.', $tagList);
    }
}
