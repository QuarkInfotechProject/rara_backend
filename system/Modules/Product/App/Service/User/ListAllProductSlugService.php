<?php

namespace Modules\Product\App\Service\User;

use Modules\Product\App\Models\Product;

class ListAllProductSlugService
{

    public function getSlugWithUpdatedAt()
    {
        return Product::where('status', 'published')
            ->select('slug', 'updated_at', 'type')
            ->get()
            ->map(function ($product) {
                return [
                    'slug' => $product->slug,
                    'type' => $product->type,
                    'updatedAt' => $product->updated_at
                ];
            });
    }


}
