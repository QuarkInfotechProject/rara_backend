<?php

namespace Modules\Media\App\Http\Controllers\Popup;

use Modules\Media\Service\Popup\IndexPopupService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class IndexPopupController extends AdminBaseController
{
    function __construct(private IndexPopupService $indexPopupService)
    {
    }

    public function __invoke()
    {
        $popups = $this->indexPopupService->getAllPopups();

        return $this->successResponse('Popups have been fetched successfully.', $popups);
    }
}
