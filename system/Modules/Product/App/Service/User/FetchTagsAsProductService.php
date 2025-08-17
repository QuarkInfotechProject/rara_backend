<?php

namespace Modules\Product\App\Service\User;

use Modules\Product\App\Models\Tag;

class FetchTagsAsProductService
{


    public function getTagsFromProductType($type): array
    {
        return Tag::where('type', $type)
            ->select('id', 'name', 'slug', 'description', 'type', 'latitude', 'longitude', 'display_order', 'zoom_level')
            ->get()
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'description' => $tag->description,
                    'type' => $tag->type,
                    'latitude' => $tag->latitude,
                    'longitude' => $tag->longitude,
                    'display_order' => $tag->display_order,
                    'zoom_level' => $tag->zoom_level,
                ];
            })
            ->whenEmpty(function () {
                return collect();
            })
            ->all();
    }

}
