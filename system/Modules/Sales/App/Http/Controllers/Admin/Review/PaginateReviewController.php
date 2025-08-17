<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Review;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Review\PaginateReviewsService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateReviewController extends AdminBaseController
{


    public function __construct(private PaginateReviewsService $paginateReviewsService)
    {
    }


    public function __invoke(Request $request)
    {
        $data = $this->paginateReviewsService->getPaginatedReviews($request->get('filters'));

        return $this->successResponse('Paginated Review has been fetched successfully.', $data);
    }
}
