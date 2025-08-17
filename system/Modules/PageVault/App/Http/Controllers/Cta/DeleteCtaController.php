<?php

namespace Modules\PageVault\App\Http\Controllers\Cta;

use Modules\PageVault\App\Service\Cta\DeleteCtaService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DeleteCtaController extends AdminBaseController
{

    public function __construct(private DeleteCtaService $deleteCtaService)
    {
    }

    public function __invoke($id)
    {
        $this->deleteCtaService->deleteCta($id);
        return $this->successResponse('Cta has been deleted successfully.');
    }
}
