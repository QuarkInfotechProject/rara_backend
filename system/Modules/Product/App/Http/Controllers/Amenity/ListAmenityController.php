<?php

namespace Modules\Product\App\Http\Controllers\Amenity;

use Illuminate\Http\Request;
use Modules\Product\App\Service\Admin\Amenities\ListAmenitiesService;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListAmenityController extends AdminBaseController
{

    public function __construct(private ListAmenitiesService $listAmenitiesService)
    {
    }


    public function __invoke(Request $request)
    {
        $list = $this->listAmenitiesService->getAmenitiesList($request->get('filters'));
        return $this->successResponse('Amenity List has been fetched successfully.', $list);
    }
}
