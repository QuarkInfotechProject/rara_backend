<?php

namespace Modules\Product\App\Service\Admin\Product;

use Modules\Product\App\Models\Tag;

class ListTagForSelectService
{
    public function getTagsForSelect()
    {
        return Tag::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name
                ];
            });
    }

}
