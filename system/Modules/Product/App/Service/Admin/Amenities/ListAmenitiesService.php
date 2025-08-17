<?php

namespace Modules\Product\App\Service\Admin\Amenities;

use Modules\Product\App\Models\Amenity;

class ListAmenitiesService
{
    public function getAmenitiesList($filters)
    {
        $query = Amenity::select('name', 'icon', 'description', 'category', 'id')
            ->orderBy('category')
            ->orderBy('name');

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        $amenities = $query->get();

        $flatAmenities = [];

        foreach ($amenities as $amenity) {
            $flatAmenities[] = [
                'name' => $amenity->name,
                'icon' => $amenity->icon,
                'description' => $amenity->description,
                'category' => $amenity->category, // Dynamic category field
                'id' => $amenity->id
            ];
        }

        return [
            'code' => 0,
            'message' => 'Amenity List has been fetched successfully.',
            'data' => $flatAmenities
        ];
    }


}
