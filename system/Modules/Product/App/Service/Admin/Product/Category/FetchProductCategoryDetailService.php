<?php

namespace Modules\Product\App\Service\Admin\Product\Category;

use Modules\Product\App\Models\ProductCategory;

class FetchProductCategoryDetailService
{
    public function getCategoryDetails($id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            throw new \Exception('Product Category not found');
        }

        // Fetch the meta using the relationship
        $entityMetadata = $category->meta()->first();

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description ?? '',
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
            'meta' => [
                'metaTitle' => $entityMetadata->meta_title ?? '',
                'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords, true) : [],
                'metaDescription' => $entityMetadata->meta_description ?? '',
            ],
        ];
    }
}
