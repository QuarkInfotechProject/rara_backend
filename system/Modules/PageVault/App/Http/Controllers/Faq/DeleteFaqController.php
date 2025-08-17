<?php

namespace Modules\PageVault\App\Http\Controllers\Faq;

use Modules\PageVault\App\Service\Faq\DeleteFaqService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DeleteFaqController extends AdminBaseController
{
    public function __construct(private DeleteFaqService $deleteFaqService)
    {
    }


    public function __invoke($id)
    {
        $this->deleteFaqService->deleteFaq($id);
        return $this->successResponse('Faq has been deleted successfully.');
    }
}
