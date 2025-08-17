<?php

namespace Modules\Blog\App\Service\Category;

use Modules\Blog\App\Models\BlogCategory;

class ListCategoryForSelectService
{

    public function getAllCategoriesForSelect(): array
    {
        $categories = BlogCategory::select('id', 'name')->get();

        $categoryList = [];
        foreach ($categories as $category) {
            $categoryList[] = [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }

        return $categoryList;
    }

}
