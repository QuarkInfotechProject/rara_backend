<?php

namespace Modules\Media\App\Http\Controllers\Popup;

use Modules\Media\Service\Popup\DestroyPopupService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;

class DestroyPopupController extends AdminBaseController
{
    public function __construct(private DestroyPopupService $destroyPopupService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->destroyPopupService->destroy($request->slug, $request->ip());

        return $this->successResponse('Popup has been deleted successfully.');
    }
}
