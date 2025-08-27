<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Modules\Product\App\Service\User\ListHeroProductForHomepageService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class ListHeroProductForHomepageController extends UserBaseController
{

    public function __construct(private ListHeroProductForHomepageService $listHeroProductForHomepageService)
    {
    }

    public function __invoke()
    {
        $data = $this->listHeroProductForHomepageService->getHeroProducts();
        return $this->successResponse('Hero Product List for homepage has been fetched successfully.', $data);
    }

}
