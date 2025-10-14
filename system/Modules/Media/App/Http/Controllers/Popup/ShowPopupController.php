<?php

namespace Modules\Media\App\Http\Controllers\Popup;

use Modules\Media\Service\Popup\ShowPopupService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ShowPopupController extends AdminBaseController
{
    function __construct(private ShowPopupService $showPopupService)
    {
    }

    function __invoke(string $slug)
     {
         $mediaCategory = $this->showPopupService->show($slug);

         return $this->successResponse('Media category has been fetched successfully.', $mediaCategory);
     }
}
