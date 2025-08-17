<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Agent;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Agent\UpdateAgentService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateAgentController extends AdminBaseController
{

    public function __construct(private UpdateAgentService $updateAgentService)
    {
    }

    public function __invoke(Request $request)
    {
       $this->updateAgentService->updateAgent($request->request->all(), $request->getClientIp());

       return $this->successResponse('Agent has been updated successfully.');
    }

}
