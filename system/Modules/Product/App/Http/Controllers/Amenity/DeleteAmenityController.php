<?php

namespace Modules\Product\App\Http\Controllers\Amenity;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Amenities\DeleteAmenityService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class DeleteAmenityController extends AdminBaseController
{

    public function __construct(private DeleteAmenityService $deleteAmenityService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->deleteAmenityService->deleteAmenity($request->get('id'), $request->getClientIp());

        return $this->successResponse('Amenity has been deleted successfully.');
    }
}
