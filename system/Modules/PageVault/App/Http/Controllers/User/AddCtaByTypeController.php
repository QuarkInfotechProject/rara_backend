<?php

namespace Modules\PageVault\App\Http\Controllers\User;

use Illuminate\Http\Request;
use Modules\PageVault\App\Service\User\AddCTAService;
use Modules\Shared\App\Http\Controllers\UserBaseController;

class AddCtaByTypeController extends UserBaseController
{

    public function __construct(private AddCTAService $addCTAService)
    {
    }


    public function __invoke(Request $request)
    {
        $this->addCTAService->addCta($request->request->all());

        return $this->successResponse('Cta has been added successfully,');
    }

}
