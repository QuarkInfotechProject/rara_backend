<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Review;

use Modules\Sales\App\Http\Service\Admin\Review\ApproveToggleReviewService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ApproveToggleReviewController extends AdminBaseController
{

    public function __construct(private ApproveToggleReviewService $approveToggleReviewService)
    {
    }


    public function __invoke($id)
    {
        $this->approveToggleReviewService->toggleApproval($id);
        return $this->successResponse('Review has been approve toggled successfully.');
    }


}
