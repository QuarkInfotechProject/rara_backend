<?php

namespace Modules\Blog\App\Service\Category;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Blog\App\Models\BlogCategory;

class PaginateCategoryService
{
    public function getPaginatedCategories(array $filters): LengthAwarePaginator
    {
        $query = BlogCategory::query();

        if (isset($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }

        $query->orderBy('created_at', 'desc');

        return $query->select('name', 'slug', 'id')->paginate();
    }

}
