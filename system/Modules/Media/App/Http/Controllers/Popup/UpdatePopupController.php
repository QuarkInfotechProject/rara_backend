<?php

namespace Modules\Media\App\Http\Controllers\Popup;

use Modules\Media\Service\Popup\UpdatePopupService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;

class UpdatePopupController extends AdminBaseController
{
    public function __construct(private UpdatePopupService $updatePopupService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updatePopupService->updatePopup($request->all(), $request->getClientIp());

        return $this->successResponse('Popup has been updated successfully.');
    }
}
