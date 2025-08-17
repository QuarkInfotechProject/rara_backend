<?php

namespace Modules\Product\App\Http\Controllers\Tag;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Modules\Product\App\Service\Admin\Tag\DeleteTagService;

class DeleteTagController extends AdminBaseController
{

    public function __construct(private DeleteTagService $deleteTagService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->deleteTagService->deleteTag($request->get('id'), $request->getClientIp());

        return $this->successResponse('Tag has been deleted successfully.');
    }

}
