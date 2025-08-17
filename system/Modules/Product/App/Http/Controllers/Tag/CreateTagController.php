<?php

namespace Modules\Product\App\Http\Controllers\Tag;
use Modules\Product\App\Service\Admin\Tag\CreateTagService;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateTagController extends AdminBaseController
{

    public function __construct(private CreateTagService $createTagService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createTagService->addTag($request->request->all(), $request->getClientIp());

        return $this->successResponse('Tag has been created successfully.');
    }

}
