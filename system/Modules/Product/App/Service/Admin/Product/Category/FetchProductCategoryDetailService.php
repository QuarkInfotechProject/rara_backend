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
                'category_name' => $category->category_name,
                'slug' => $category->slug,
                'description' => $category->description,
                'status' => $category->status,
                'meta_title' => $category->meta_title,
                'meta_description' => $category->meta_description,
                'keywords' => $category->keywords,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ];
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
