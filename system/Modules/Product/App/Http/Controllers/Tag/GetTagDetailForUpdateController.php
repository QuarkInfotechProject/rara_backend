<?php

namespace Modules\Product\App\Http\Controllers\Tag;

use Modules\Product\App\Service\Admin\Tag\GetDetailTagForUpdateService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetTagDetailForUpdateController extends AdminBaseController
{

    public function __construct(private GetDetailTagForUpdateService $getDetailTagForUpdateService)
    {
    }

    public function __invoke($id)
    {
        $tagDetail = $this->getDetailTagForUpdateService->getTagDetailForUpdate($id);

        return $this->successResponse('Tag detail has been fetched successfully.', $tagDetail);
    }

}
