<?php

namespace Modules\Product\App\Http\Controllers\Product\Tour;

use Modules\Shared\App\Http\Controllers\AdminBaseController;

class FetchTourDetailController extends AdminBaseController
{

    public function __construct(private \Modules\Product\App\Service\Admin\Product\Tour\FetchTourDetailService $fetchTourDetailService )
    {
    }


    public function __invoke($id)
    {
        $detail = $this->fetchTourDetailService->getTourDetails($id);
        return $this->successResponse('Detail for tour has been fetched successfully.', $detail);
    }


}
