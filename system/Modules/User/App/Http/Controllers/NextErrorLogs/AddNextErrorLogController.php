<?php

namespace Modules\User\App\Http\Controllers\NextErrorLogs;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\UserBaseController;
use Modules\User\Service\NextErrorLogs\NextErrorLogsService;

class AddNextErrorLogController extends UserBaseController
{

    public function __construct(private NextErrorLogsService $nextErrorLogsService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->nextErrorLogsService->addNextErrorLogs($request->request->all());
        return $this->successResponse('Error Logs has been created successfully.');
    }

}
