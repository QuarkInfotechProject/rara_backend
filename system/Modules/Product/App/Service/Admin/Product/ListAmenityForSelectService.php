<?php

namespace Modules\Product\App\Service\Admin\Product;

use Modules\Product\App\Models\Amenity;

class ListAmenityForSelectService
{

    public function getAmenitiesByCategoryForSelect($category)
    {
        return Amenity::where('category', $category)
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($amenity) {
                return [
                    'id' => $amenity->id,
                    'name' => $amenity->name
                ];
            });
    }


}
