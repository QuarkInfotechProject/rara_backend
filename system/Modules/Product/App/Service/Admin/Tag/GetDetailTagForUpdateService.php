<?php

namespace Modules\Product\App\Service\Admin\Tag;

use Modules\Product\App\Models\Tag;

class GetDetailTagForUpdateService
{

    public function getTagDetailForUpdate(int $id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            throw new \Exception('Tag Not found');
        }

        return [
            'name' => $tag->name,
            'slug' => $tag->slug,
            'description' => $tag->description,
            'type' => $tag->type,
            'latitude' => $tag->latitude,
            'longitude' => $tag->longitude,
            'display_order' => $tag->display_order,
            'zoom_level' => $tag->zoom_level,
            'tagProfile' => $this->getMediaFiles($tag)
        ];
    }

    private function getMediaFiles($tag)
    {
        $baseImageFiles = $tag->filterFiles('tagProfile')->get();

        $baseImage = $baseImageFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'baseImageUrl' => $file->path . '/' . $file->temp_filename,
            ];
        })->first();

        return $baseImage ?? '';
    }
}
