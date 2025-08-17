<?php

namespace Modules\Product\App\Http\Controllers\Tag;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Tag\UpdateTagService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateTagController extends AdminBaseController
{
    public function __construct(private UpdateTagService $updateTagService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updateTagService->updateTag($request->request->all(), $request->getClientIp());

        return $this->successResponse('Tag has been updated successfully.');
    }

}
