<?php

namespace Modules\Product\App\Http\Controllers\User\Product;

use Illuminate\Http\Request;
use Modules\Product\App\Service\User\GetProductDetailCommentService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class GetProductDetailCommentController extends UserBaseController
{

    public function __construct(private GetProductDetailCommentService $getProductDetailCommentService)
    {
    }


    public function __invoke(Request $request, $slug)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 10);
        $review = $this->getProductDetailCommentService->getReviews($slug, $perPage, $page);

        return $this->successResponse('Review has been fetched successfully.', $review);
    }

}
