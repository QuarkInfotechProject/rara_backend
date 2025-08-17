<?php

namespace Modules\PageVault\App\Http\Controllers\Faq;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\Faq\AddFaqService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class AddFaqController extends AdminBaseController
{
    public function __construct(private AddFaqService $addFaqService)
    {

    }

    public function __invoke(Request $request)
    {
        $this->addFaqService->addFaq($request->request->all());
        return $this->successResponse('Faq has been added successfully.');
    }
}
