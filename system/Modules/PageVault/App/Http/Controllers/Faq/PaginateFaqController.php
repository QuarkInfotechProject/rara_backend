<?php

namespace Modules\PageVault\App\Http\Controllers\Faq;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\Faq\PaginateFaqService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateFaqController extends AdminBaseController
{
    public function __construct(private PaginateFaqService $paginateFaqService)
    {

    }

    public function __invoke(Request $request)
    {
        $paginate = $this->paginateFaqService->listOurTeam($request->get('filters'));
        return $this->successResponse('Paginate has been fetched successfully.', $paginate);
    }
}
