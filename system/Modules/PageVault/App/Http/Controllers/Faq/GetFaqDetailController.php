<?php

namespace Modules\PageVault\App\Http\Controllers\Faq;

use Modules\PageVault\App\Service\Faq\GetDetailFaqService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetFaqDetailController extends AdminBaseController
{
    public function __construct(private GetDetailFaqService $getDetailFaqService)
    {
    }

    public function __invoke($id)
    {
        $detail = $this->getDetailFaqService->getFaqDetailById($id);
        return $this->successResponse('Faq Detail has been fetched successfully.', $detail);
    }
}
