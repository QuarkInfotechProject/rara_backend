<?php

namespace Modules\Product\App\Http\Controllers\Amenity;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Amenities\CreateAmenityService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class CreateAmenityController extends AdminBaseController
{
    public function __construct(private CreateAmenityService $createAmenitiesService)
    {
    }

    public function __invoke(Request $request)
    {
        $this->createAmenitiesService->addAmenities($request->request->all(), $request->getClientIp());

        return $this->successResponse('Amenity has been created successfully.');
    }


}
