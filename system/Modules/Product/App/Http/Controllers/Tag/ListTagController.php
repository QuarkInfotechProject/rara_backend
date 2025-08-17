<?php

namespace Modules\Product\App\Http\Controllers\Tag;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListTagController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Tag\ListTagService $listTagService)
    {
    }

    public function __invoke(Request $request)
    {
        $list = $this->listTagService->getTagsList($request->get('filters'));

        return $this->successResponse('Tag List has been fetched successfully.', $list);
    }

}
