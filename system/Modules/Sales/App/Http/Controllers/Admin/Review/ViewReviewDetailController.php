<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Review;

use Modules\Sales\App\Http\Service\Admin\Review\ViewReviewDetailService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ViewReviewDetailController extends AdminBaseController
{

    public function __construct(private ViewReviewDetailService $viewReviewDetailService)
    {
    }


    public function __invoke($id)
    {
        $data = $this->viewReviewDetailService->getReviewDetails($id);
        return $this->successResponse('Review Detail has been fetched successfully.', $data);
    }

}
