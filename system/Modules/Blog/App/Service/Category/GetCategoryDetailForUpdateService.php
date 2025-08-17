<?php

namespace Modules\Blog\App\Service\Category;

use Modules\Blog\App\Models\BlogCategory;

class GetCategoryDetailForUpdateService
{

    public function getCategoryDetailForUpdate(int $id)
    {
        $postCategory = BlogCategory::find($id);

        if (!$postCategory) {
            throw new \Exception('Blog Category Not found');
        }

        $entityMetadata = $postCategory->meta()->first();

        return [
            'name' => $postCategory->name,
            'slug' => $postCategory->slug,
            'description' => $postCategory->description,
            'meta' => [
                'metaTitle' => $entityMetadata->meta_title ?? '',
                'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords) : null,
                'metaDescription' => $entityMetadata->meta_description ?? "",
            ],
        ];

    }

}
