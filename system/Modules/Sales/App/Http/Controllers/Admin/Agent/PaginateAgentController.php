<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Agent;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Agent\PaginateAgentService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class PaginateAgentController extends AdminBaseController
{

    public function __construct(private PaginateAgentService $paginateAgentService)
    {
    }

    public function __invoke(Request $request)
    {
        $paginateAgent = $this->paginateAgentService->paginateAgents($request->get('filters'));

        return $this->successResponse('Paginated agent has been fetched successfully.', $paginateAgent);
    }

}
