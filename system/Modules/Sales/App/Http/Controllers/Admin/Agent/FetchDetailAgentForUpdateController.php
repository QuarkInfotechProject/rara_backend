<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Agent;

use Modules\Sales\App\Http\Service\Admin\Agent\FetchDetailAgentForUpdateService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class FetchDetailAgentForUpdateController extends AdminBaseController
{

    public function __construct(private FetchDetailAgentForUpdateService $fetchDetailAgentForUpdateService)
    {
    }

    public function __invoke($id)
    {
        $detail = $this->fetchDetailAgentForUpdateService->getAgentDetailById($id);
        return $this->successResponse('Agent detail has been fetched successfully.', $detail);
    }

}
