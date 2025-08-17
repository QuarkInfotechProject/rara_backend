<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Agent;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Agent\CreateAgentService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateAgentController extends AdminBaseController
{

    public function __construct(private CreateAgentService $createAgentService)
    {
    }

    public function __invoke(Request $request )
    {
        $this->createAgentService->createAgent($request->request->all(), $request->getClientIp());
        return $this->successResponse('Agent has been created successfully.');
    }

}
