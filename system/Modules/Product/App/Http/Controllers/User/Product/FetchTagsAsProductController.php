<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\FetchTagsAsProductService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class FetchTagsAsProductController extends UserBaseController
{

    public function __construct(private FetchTagsAsProductService $fetchTagsAsProductService)
    {
    }

    public function __invoke($type)
    {
        $tags = $this->fetchTagsAsProductService->getTagsFromProductType($type);
        return $this->successResponse('Tags has been fetched successfully.', $tags);
    }
}
