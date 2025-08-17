<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Booking;

use Modules\Sales\App\Http\Service\Admin\Booking\ListAgentForSelectService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListAgentForSelectController extends AdminBaseController
{

    public function __construct(private ListAgentForSelectService $listAgentForSelectService)
    {
    }

    public function __invoke()
    {
        $list = $this->listAgentForSelectService->getAgentListForSelect();

        return $this->successResponse('Agent list has been fetched successfully.', $list);
    }
}
