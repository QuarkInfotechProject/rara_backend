<?php

namespace Modules\Media\App\Http\Controllers\Popup;

use Illuminate\Http\Request;
use Modules\Media\Service\Popup\CreatePopupService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreatePopupController extends AdminBaseController
{
    function __construct(private CreatePopupService $createPopupService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createPopupService->createPopup($request->all(), $request->getClientIp(), $request);
        return $this->successResponse('Popup has been created successfully.');
    }
}
