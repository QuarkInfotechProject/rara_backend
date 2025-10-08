<?php

namespace Modules\Product\App\Service\Admin\Product\Category;

use Modules\Product\App\Models\ProductCategory;

class FetchProductCategoryDetailService
{
    public function getCategoryDetails($id)
    {
        try {
            $category = ProductCategory::findOrFail($id);

            return [
                'id' => $category->id,
                'name' => $category->category_name,
                'slug' => $category->slug,
                'description' => $category->description ?? '',
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                'meta' => [
                    'metaTitle' => $category->meta_title ?? '',
                    'keywords' => $category->keywords ?? [],
                    'metaDescription' => $category->meta_description ?? '',
                ],
            ];
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
