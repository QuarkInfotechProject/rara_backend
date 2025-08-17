<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListExperienceTagsForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListExperienceTagsForHomepageController extends UserBaseController
{

    public function __construct(private ListExperienceTagsForHomepageService $listExperienceTagsForHomepageService)
    {
    }



    public function __invoke()
    {
        $data = $this->listExperienceTagsForHomepageService->execute();
        return $this->successResponse('Experience Tags List has been fetched successfully.', $data);
    }


}
