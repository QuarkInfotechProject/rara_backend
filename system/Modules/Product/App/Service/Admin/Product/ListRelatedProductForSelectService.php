<?php

namespace Modules\Product\App\Service\Admin\Product;

use Modules\Product\App\Models\Product;

class ListRelatedProductForSelectService
{
    public function getRelatedHomestaysForSelect($type)
    {
        return Product::where('status', 'published')
            ->where('type', $type)
            ->select('id', 'name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name
                ];
            });
    }

}
