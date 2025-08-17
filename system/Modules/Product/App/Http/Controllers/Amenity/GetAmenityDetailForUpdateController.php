<?php

namespace Modules\Product\App\Http\Controllers\Amenity;

use Modules\Product\App\Service\Admin\Amenities\GetAmenitiesDetailForUpdateService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class GetAmenityDetailForUpdateController extends AdminBaseController
{
    public function __construct(private GetAmenitiesDetailForUpdateService $getAmenitiesDetailForUpdateService)
    {
    }

    public function __invoke(int $id)
    {
        $amenityDetail = $this->getAmenitiesDetailForUpdateService->getAmenitiesDetailForUpdate($id);

        return $this->successResponse('Amenity detail has been fetched successfully.', $amenityDetail);
    }
}
