<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Agent;

use Modules\Sales\App\Http\Service\Admin\Agent\EnableDisableAgentService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DisableEnableAgentController extends AdminBaseController
{

    public function __construct(private EnableDisableAgentService $disableAgentService)
    {
    }

    public function __invoke($id)
    {
        $this->disableAgentService->disableAgentById($id);

        return $this->successResponse('Agent status has been changed successfully.');
    }

}
