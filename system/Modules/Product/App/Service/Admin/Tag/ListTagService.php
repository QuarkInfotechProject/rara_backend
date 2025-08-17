<?php

namespace Modules\Product\App\Service\Admin\Tag;

use Modules\Product\App\Models\Tag;

class ListTagService
{
    public function getTagsList($filter = null)
    {
        $type = $filter['type'] ?? null;

        $query = Tag::select('name', 'slug', 'description', 'type', 'id', 'display_order')
            ->orderBy('name');

        if ($type) {
            $query->where('name', 'like', "%{$type}%")
                ->orWhere('description', 'like', "%{$type}%");
        }

        return $query->get();
    }
}
