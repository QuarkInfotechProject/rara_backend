<?php

namespace Modules\PageVault\App\Http\Controllers\Cta;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\Cta\AddCtaService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class AddCtaController extends AdminBaseController
{

    public function __construct(private AddCtaService $addCtaService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->addCtaService->addCta($request->request->all());
        return $this->successResponse('Cta has been added successfully.');
    }

}
