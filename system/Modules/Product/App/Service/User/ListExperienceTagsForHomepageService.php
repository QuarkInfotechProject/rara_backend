<?php

namespace Modules\Product\App\Service\User;

use Modules\Product\App\Models\Tag;

class ListExperienceTagsForHomepageService
{
    public function execute()
    {
        $whyUsItems = Tag::select('id', 'name', 'slug', 'description', 'type', 'latitude', 'longitude', 'display_order', 'zoom_level')
            ->where('type', 'experience')
            ->orderBy('display_order')
            ->get();

        return $whyUsItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => $item->description,
                'type' => $item->type,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'display_order' => $item->display_order,
                'zoom_level' => $item->zoom_level,
                'featuredImage' => $this->getMediaFiles($item)
            ];
        });
    }

    private function getMediaFiles($whyUs)
    {
        $baseImageFiles = $whyUs->filterFiles('tagProfile')->get();
        $baseImage = $baseImageFiles->map(function ($file) {
            return $file->path . '/' . $file->temp_filename;
        })->first();

        return $baseImage ?? '';
    }

}
