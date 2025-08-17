<?php

namespace Modules\Product\App\Service\Admin\Amenities;

use Modules\Product\App\Models\Amenity;

class GetAmenitiesDetailForUpdateService
{

    public function getAmenitiesDetailForUpdate(int $id)
    {
        $amenities = Amenity::find($id);

        if (!$amenities) {
            throw new \Exception('Amenity Not found');
        }

        return [
            'name' => $amenities->name,
            'icon' => $amenities->icon,
            'description' => $amenities->description,
            'category' => $amenities->category
        ];
    }

}
