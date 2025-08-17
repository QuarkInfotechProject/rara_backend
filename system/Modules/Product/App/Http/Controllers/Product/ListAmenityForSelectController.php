<?php

namespace Modules\Product\App\Http\Controllers\Product;

use Illuminate\Http\Request;
use Modules\Shared\App\Http\Controllers\AdminBaseController;

class ListAmenityForSelectController extends AdminBaseController
{
    public function __construct(private \Modules\Product\App\Service\Admin\Product\ListAmenityForSelectService $listAmenityForSelectService)
    {
    }

    public function __invoke(Request $request)
    {
        $amenityList = $this->listAmenityForSelectService->getAmenitiesByCategoryForSelect($request->get('filters'));

       return $this->successResponse('Amenity for select has been fetched', $amenityList);
    }

}
