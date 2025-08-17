<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Review;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Review\ReplyToReviewService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ReplyToReviewController extends AdminBaseController
{
    public function __construct(private ReplyToReviewService $replyToReviewService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->replyToReviewService->replyToReview($request->request->all());

        return $this->successResponse('Review has been replied successfully.');
    }


}
