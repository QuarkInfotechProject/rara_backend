<?php

namespace Modules\Sales\App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use Modules\Sales\App\Http\Service\Admin\Dashboard\TopCountriesService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class TopCountriesController extends AdminBaseController
{

    public function __construct(private TopCountriesService $topCountriesService)
    {
    }

    public function __invoke(Request $request)
    {
        $data = $this->topCountriesService->getTopCountries($request->get('startDate'), $request->get('endDate'));

        return $this->successResponse('Data fetched successfully.', $data);

    }
}
