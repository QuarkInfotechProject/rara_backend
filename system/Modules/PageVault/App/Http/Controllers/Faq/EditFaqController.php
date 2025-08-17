<?php

namespace Modules\PageVault\App\Http\Controllers\Faq;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\Faq\EditFaqService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class EditFaqController extends AdminBaseController
{
    public function __construct(private EditFaqService $editFaqService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->editFaqService->editOurFaq($request->request->all());

        return $this->successResponse('Faq has been edited successfully.');
    }
}
