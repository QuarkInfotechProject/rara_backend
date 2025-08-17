<?php

namespace Modules\Product\App\Http\Controllers\Amenity;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Amenities\UpdateAmenitiesService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class UpdateAmenityController extends AdminBaseController
{
    public function __construct(private UpdateAmenitiesService $updateAmenitiesService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->updateAmenitiesService->updateAmenity($request->request->all(), $request->getClientIp());

        return $this->successResponse('Amenity has been updated successfully.');
    }
}
